DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_billClosing`(IN `exec_date` DATE)
    NO SQL
BEGIN

DECLARE log_id INT;

START TRANSACTION;

INSERT INTO exec_log (name,class) VALUES (CONCAT('請求データ作成-',CURDATE(),"-",CURTIME()),1);

SET log_id = (SELECT MAX(id) FROM exec_log); 

DROP TEMPORARY TABLE IF EXISTS temp1;
CREATE TEMPORARY TABLE temp1 AS  
  SELECT 0 AS invoices_id, 0 AS invoice_masters_id, a.id AS bills_id, b.id AS subject_id, b.client_id, exec_date AS closing_date
    FROM bills a, subjects b WHERE a.subject_id = b.id and a.subject_id in 
      (SELECT id FROM v_subjects WHERE bill_closing = DAYOFMONTH(exec_date) AND subject_status LIKE '0_________');

DELETE FROM temp1 WHERE closing_date = exec_date AND bills_id IN (SELECT bills_id FROM invoice_details);

DROP TEMPORARY TABLE IF EXISTS temp2;
CREATE TEMPORARY TABLE temp2 AS 
  SELECT v_client_id AS client_id, exec_date AS closing_date FROM v_subjects 
    WHERE bill_closing = DAYOFMONTH(exec_date) AND subject_status LIKE '0_________' GROUP BY v_client_id;

DROP TEMPORARY TABLE IF EXISTS temp2;
CREATE TEMPORARY TABLE temp2 AS SELECT client_id FROM temp1 GROUP BY client_id;

DELETE FROM temp2 WHERE client_id IN (SELECT client_id FROM invoices WHERE closing_date=exec_date);

INSERT INTO invoices (client_id,closing_date,exec_log_id) SELECT client_id, exec_date, log_id FROM temp2;

UPDATE temp1 INNER JOIN invoices ON temp1.client_id = invoices.client_id SET temp1.invoices_id = invoices.id; 

DROP TEMPORARY TABLE IF EXISTS temp3;
CREATE TEMPORARY TABLE temp3 AS SELECT invoices_id,invoice_masters_id,subject_id,bills_id FROM  temp1 GROUP BY invoices_id, subject_id;

DELETE temp3 FROM temp3 INNER JOIN invoice_masters ON temp3.subject_id = invoice_masters.subject_id ;

INSERT INTO invoice_masters (invoices_id,subject_id) SELECT invoices_id,subject_id FROM  temp1 GROUP BY invoices_id, subject_id;

UPDATE temp3 INNER JOIN invoice_masters ON temp3.subject_id = invoice_masters.subject_id SET temp3.invoice_masters_id = invoice_masters.id; 

INSERT INTO invoice_details (invoice_masters_id, bills_id) SELECT invoice_masters_id, bills_id FROM  temp3;

COMMIT;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_closing`(IN `in_client_id` BIGINT, IN `in_closing_date` DATE, OUT `out_result` JSON)
BEGIN

  /* カーソルがデータセットの最後に達したか判定するための変数を宣言 */
  DECLARE done INT DEFAULT FALSE;
  
  /* 案件情報のカーソルの定義 */
  DECLARE w_subject_id BIGINT;
  DECLARE w_expenses, w_pay_ratio INT;
  DECLARE cur_subject CURSOR FOR SELECT id,pay_ratio,expenses FROM subjects 
    WHERE task_class BETWEEN 21 AND 29 AND client_id = in_client_id AND end_date <= in_closing_date;
  
  /* カーソルがデータセットの最後に達したときの動作を制御 */
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  /* トランザクション処理の開始 */
  START TRANSACTION;

  /* 請求情報の存在をチェック */
  IF (SELECT COUNT(*) FROM invoices WHERE client_id = in_client_id AND closing_date = in_closing_date) = 0 THEN
    /* 請求情報の追加 */
    INSERT INTO invoices (client_id,closing_date) VALUES (in_client_id,in_closing_date);
    /*  */
    SELECT @invoice_id := LAST_INSERT_ID();       
  ELSE
    SELECT @invoice_id := id FROM invoices WHERE client_id = in_client_id AND closing_date = in_closing_date;
  END IF;

  /* 案件情報のカーソルの処理 */
  OPEN cur_subject;
    loop_subjec:LOOP 
      FETCH cur_subject INTO w_subject_id,w_pay_ratio,w_expenses;

      /* カーソルからの読み出しが最後に達していればループを抜ける */
      IF done THEN 
        LEAVE loop_subjec;
      END IF;
 
      /* 請求マスターの存在チェック */
      IF (SELECT COUNT(*) FROM invoice_masters WHERE invoice_id = @invoice_id AND subject_id = w_subject_id) = 0 THEN	
        /* 請求マスターの追加 */
        INSERT INTO invoice_masters (invoice_id, subject_id, closing_date, tax_state, amount, tax, discount, ratio, bill_amount)
          SELECT @invoice_id, w_subject_id, in_closing_date, tax_state, bill_amount, tax, discount, pay_ratio, bill_amount 
          FROM subjects WHERE id = w_subject_id;

        /*  */
        SELECT @invoice_master_id := LAST_INSERT_ID();

        /*  */
        CREATE TEMPORARY TABLE t_tasks AS
          SELECT task_id AS id, 100 - SUM(yield_ratio) AS remain_ratio, SUM(yield_amount) AS done_amount FROM invoice_details
            WHERE invoice_master_id IN (SELECT id FROM invoice_masters WHERE subject_id = w_subject_id)
            GROUP BY task_id;  

        /* 請求明細の追加処理 */
        INSERT INTO invoice_details (invoice_master_id,task_id, yield_ratio, yield_amount) 
          SELECT
	      @invoice_master_id, 
              a.id AS task_id,
              CASE WHEN IFNULL(b.remain_ratio,100) <= 0 THEN 0 
                WHEN IFNULL(b.remain_ratio,100) >= w_pay_ratio THEN w_pay_ratio 
                ELSE b.remain_ratio END AS yield_ratio,
              amount 
            FROM tasks a LEFT JOIN t_tasks b ON a.id = b.id 
            WHERE a.subject_id = w_subject_id 
            ORDER BY a.id;

        /* 一時テーブルを削除 */
        DROP TABLE t_tasks;
        
        /* 請求明細の明細金額を支払い率で再計算し更新 */
        UPDATE invoice_details 
          SET yield_amount = ROUND((yield_amount * yield_ratio) / 100) 
          WHERE invoice_master_id = @invoice_master_id;

        /* 請求明細の明細金額の合計を取得 */
        SELECT @invoice_amount := SUM(yield_amount) FROM invoice_details 
          WHERE invoice_master_id = @invoice_master_id;
	
	/* 全体の日払い比率を算出 */
	SET @ratio = ROUND((@invoice_amount/w_expenses)*100,1); 

        IF @invoice_amount != 0 THEN
          /* 請求マスターの請求金額を請求明細の明細金額の合計で更新 */
          UPDATE invoice_masters SET amount=@invoice_amount, ratio=@ratio, 
		discount=ROUND((discount * @ratio)/100), bill_amount=ROUND((bill_amount * @ratio)/100), 
		tax=ROUND((tax * @ratio)/100) WHERE id = @invoice_master_id;
        ELSE
          /* 請求マスターの請求金額が0円の場合は請求マスターを削除する */
          DELETE FROM invoice_masters WHERE id = @invoice_master_id;        
        END IF;        
      END IF;  
    END LOOP;   
  CLOSE cur_subject;

  /* 合計額を算出 */
  SELECT @total := IFNULL(SUM(amount),0) FROM invoice_masters WHERE invoice_id = @invoice_id;

  IF @total = 0 THEN
    /* 最終的に請求金額が0円の場合は請求情報を削除する */
    DELETE FROM invoices WHERE id = @invoice_id;
  ELSE
    /* 請求情報の合計金額と消費税を更新する */
    UPDATE invoices SET 
      amount = @total,
      tax = CASE tax_state 
              WHEN 1 THEN ROUND((@total * 10) / 110) 
              WHEN 2 THEN ROUND((@total * 10) / 100)
              ELSE 0
            END
      WHERE id = @invoice_id;
 
  END IF;

  /* トランザクション処理のコミット */
  COMMIT;
  
  /*
  set out_result = (SELECT * FROM invoices WHERE id = @invoice_id);
*/
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_closing_old`(IN `in_closing_date` DATE, IN `in_client_id` INT)
    NO SQL
BEGIN

  DECLARE done INT DEFAULT FALSE;

  DECLARE w_schedule_id BIGINT;
  DECLARE w_end_date DATE;
  
  DECLARE w_bill_closing DATE;
  
  DECLARE cur1 CURSOR FOR SELECT id,end_date FROM schedules WHERE SUBSTER(status,1,1) = '0';
  OPEN cur1;
  LOOP
    FETCH cur1 INTO w_schedule_id,w_end_date;
    END LOOP;
  CLOSE cur1;
  


END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_closing_old1`(IN `in_closing_date` DATE, IN `in_client_id` INT, OUT `out_result` JSON)
    NO SQL
BEGIN

  DECLARE done INT DEFAULT FALSE;

  DECLARE w_subject_id, w_tasks_id, w_invoices_id, w_invoice_masters_id BIGINT;
  DECLARE w_client_id, w_clients_bill_issue, w_clients_bill_payment INT;
  DECLARE w_subject_expenses, w_subject_discount, w_subject_pay_ratio, w_subject_pay_amount INT;
  DECLARE w_tasks_amount,w_invoice_masters_amount,w_invoice_masters_pay_ratio,w_invoice_masters_tax INT;
  
  DECLARE cur1 CURSOR FOR SELECT id,bill_issue,bill_payment FROM clients WHERE bill_closing = DAY(in_closing_date);
  DECLARE cur2 CURSOR FOR SELECT id,expenses,discount,pay_ratio,pay_amount FROM subjects WHERE client_id = in_client_id AND start_date<=in_closing_date;
  DECLARE cur3 CURSOR FOR SELECT id,IFNULL(amount,0) AS amount FROM tasks WHERE class = 11 AND subject_id=w_subject_id AND startDate<=in_closing_date;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

  START TRANSACTION;
  
  select w_client_id,in_closing_date;
  SELECT id,expenses,discount,pay_ratio,pay_amount FROM subjects WHERE client_id = w_client_id AND start_date<=in_closing_date;

  #--------------------------------------------------
  #カーソル１
  #--------------------------------------------------
  OPEN cur1;
  read_loop: LOOP
    FETCH cur1 INTO w_client_id,w_clients_bill_issue,w_clients_bill_payment;
    IF done THEN
      LEAVE read_loop;
    END IF;
    #請求マスターの生成
    IF (SELECT COUNT(*) FROM invoices WHERE client_id=w_client_id AND closing_date=in_closing_date) = 0 THEN
      INSERT INTO invoices (client_id,closing_date,issue_date,payment_date,amount) 
        VALUES (w_client_id,in_closing_date,
          CASE WHEN w_clients_bill_issue <= DAY(in_closing_date) 
            THEN DATE_ADD(LAST_DAY(in_closing_date), INTERVAL w_clients_bill_issue DAY) 
            ELSE DATE_ADD(DATE_FORMAT(in_closing_date , '%Y-%m-01' ) , INTERVAL w_clients_bill_issue -1 DAY) END, 
          CASE WHEN w_clients_bill_payment <= DAY(in_closing_date) 
            THEN DATE_ADD(LAST_DAY(in_closing_date) , INTERVAL w_clients_bill_payment DAY) 
            ELSE DATE_ADD(DATE_FORMAT(in_closing_date , '%Y-%m-01' ) , INTERVAL w_clients_bill_payment -1 DAY) END,0
      );
      SET w_invoices_id = (SELECT MAX(id) FROM invoices);
    ELSE
      SET w_invoices_id = (SELECT id FROM invoices WHERE client_id=w_client_id AND closing_date=in_closing_date);
      DELETE FROM invoice_masters WHERE invoices_id = w_invoices_id;
    END IF;
    #--------------------------------------------------
    #カーソル２
    #--------------------------------------------------
    OPEN cur2;
    read_loop2: LOOP
      FETCH cur2 INTO w_subject_id, w_subject_expenses, w_subject_discount, w_subject_pay_ratio, w_subject_pay_amount;
      IF done THEN
        SET done = FALSE;
        LEAVE read_loop2;
      END IF;
      #-------------------------------------------------
      #請求案件の生成
      IF (SELECT COUNT(*) FROM invoice_masters WHERE closing_date=in_closing_date AND subject_id=w_subject_id) = 0 THEN
        INSERT INTO invoice_masters (invoices_id,subject_id,closing_date,tax_state,amount,tax,discount,discount_ratio) 
          VALUES (w_invoices_id,w_subject_id,in_closing_date,1,0,0,0,0);
	SET w_invoice_masters_id = (SELECT MAX(id) FROM invoice_masters); 
        #--------------------------------------------------
        #カーソル３
        #--------------------------------------------------
        OPEN cur3;
        read_loop3: LOOP
          FETCH cur3 INTO w_tasks_id,w_tasks_amount;
          IF done THEN
            SET done = FALSE;
            LEAVE read_loop3;
          END IF;
          #請求残の算出
          SET @remaining_amount=0,@remaining_ratio=0;
          SELECT @remaining_amount:=IFNULL(SUM(yield_amount),0), @remaining_ratio:=IFNULL(SUM(yield_ratio),0) FROM invoice_details WHERE tasks_id = w_tasks_id GROUP BY tasks_id ;
          #請求明細の生成
          IF @remaining_amount >= w_tasks_amount THEN
            INSERT INTO invoice_details (class,invoice_masters_id,tasks_id,yield_ratio,yield_amount) 
              VALUES (11,w_invoice_masters_id,w_tasks_id,0,0);
          ELSEIF w_subject_pay_ratio + @remaining_ratio > 100 THEN
            INSERT INTO invoice_details (class,invoice_masters_id,tasks_id,yield_ratio,yield_amount) 
              VALUES (11,w_invoice_masters_id,w_tasks_id,100-@remaining_ratio,ROUND((w_tasks_amount*(100-@remaining_ratio))/100));               
          ELSE 
            INSERT INTO invoice_details (class,invoice_masters_id,tasks_id,yield_ratio,yield_amount) 
              VALUES (11,w_invoice_masters_id,w_tasks_id,w_subject_pay_ratio,ROUND((w_tasks_amount*w_subject_pay_ratio)/100));
          END IF;
        END LOOP;
        CLOSE cur3;
        IF (SELECT COUNT(*) FROM invoice_details WHERE invoice_masters_id = w_invoice_masters_id) > 0 THEN
          #請求案件の更新
          SET w_invoice_masters_amount=(SELECT SUM(yield_amount) FROM invoice_details WHERE invoice_masters_id = w_invoice_masters_id);
          SET w_invoice_masters_pay_ratio = ROUND((w_invoice_masters_amount / w_subject_expenses) * 10000);
          SET w_subject_discount = ROUND((w_subject_discount * w_invoice_masters_pay_ratio) / 10000);
          call p_getTax(in_closing_date,w_invoice_masters_amount-w_subject_discount,1,@w_invoice_masters_tax);
          UPDATE invoice_masters SET amount=IFNULL(w_invoice_masters_amount,0), tax=IFNULL(@w_invoice_masters_tax,0),discount=IFNULL(w_subject_discount,0),discount_ratio=IFNULL(w_invoice_masters_pay_ratio,0) WHERE id=w_invoice_masters_id;   
          DELETE FROM invoice_masters WHERE amount=0 AND id=w_invoice_masters_id;
        ELSE
          DELETE FROM invoice_masters WHERE id=w_invoice_masters_id;
        END IF;
      END IF;
    END LOOP;
    CLOSE cur2;
    #--------------------------------------------------
    #
    IF (SELECT COUNT(*) FROM invoice_masters WHERE id = w_invoice_masters_id) > 0 THEN
      UPDATE invoices SET amount = (SELECT IFNULL(SUM(amount)+SUM(tax)-SUM(discount)+SUM(adjust_amount),0) FROM invoice_masters WHERE invoices_id = w_invoices_id) WHERE id = w_invoices_id;
    ELSE
      DELETE FROM invoices WHERE id = w_invoices_id;
    END IF;
  END LOOP;
  CLOSE cur1;
  #--------------------------------------------------
 
  COMMIT;
  
   set out_result = (SELECT name FROM invoices WHERE id = w_invoices_id);


END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_closing_subject`(IN `in_subject_id` BIGINT, IN `in_closing_date` DATE)
    NO SQL
BEGIN

SET @tax_state = 1;

DROP TEMPORARY TABLE IF EXISTS w_subject;  
CREATE TEMPORARY TABLE w_subject AS 
    SELECT * FROM subjects WHERE id=in_subject_id;
    
SELECT MIN(clients_id) INTO @client_id FROM w_subject;

SELECT pay_ratio INTO @pay_raitio FROM clients WHERE id = @client_id;
    
DROP TEMPORARY TABLE IF EXISTS w_tasks;  
CREATE TEMPORARY TABLE w_tasks AS 
    SELECT * FROM tasks WHERE subject_id=in_subject_id;

DROP TEMPORARY TABLE IF EXISTS w_invoice_details;
CREATE TEMPORARY TABLE w_invoice_details AS 
  SELECT id AS tasks_id,@pay_raitio AS yield_ratio,ROUND((amount * @pay_raitio)/100) AS yield_amount FROM tasks WHERE subject_id=in_subject_id;

SELECT SUM(yield_amount) INTO @total FROM w_invoice_details;

SELECT ROUND(discount * (@total / expenses)) ,ROUND((@total / expenses) * 10000) INTO @discount,@discount_ratio FROM w_subject;

CALL p_getTax(in_closing_date,@total-@discount,@tax_state,@tax);


INSERT INTO invoice_masters (subject_id,closing_date,tax_state,amount,tax,discount,discount_ratio) 
  SELECT id AS subject_id,in_closing_date AS closing_date,@tax_state AS tax_state,@total AS amount,@tax AS tax,@discount AS discount,@discount_ratio AS discount_ratio FROM w_subject;

SELECT * FROM w_subject;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_create_pass`(IN `length` INT)
    NO SQL
begin
declare i int default 0;
declare result text default '';
declare char_set text default '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!#$%&=?}{[]';

while i <= length do
set result = concat(
            result,
            substring(char_set, ceil(rand() * char_length(char_set)), 1)
        );
  set i = i + 1;
end while;
select result;
end$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_getBillClientList`(IN `IN_STATE` INT, IN `IN_DATE` VARCHAR(10))
BEGIN

  DECLARE w_subject_id BIGINT;
  DECLARE w_bill_closing INT;
  DECLARE w_pay_ratio DECIMAL(7,4);

  CREATE TEMPORARY TABLE tmp_invoice_masters AS SELECT subject_id,SUM(ratio) AS pay_ratio FROM invoice_masters GROUP BY subject_id; 

  CREATE TEMPORARY TABLE tmp_schedules AS SELECT subject_id,SUM(pay_ratio) AS bill_ratio FROM schedules WHERE start_date<=IN_DATE GROUP BY subject_id; 

  UPDATE tmp_schedules AS t1 INNER JOIN tmp_invoice_masters t2 ON t1.subject_id=t2.subject_id SET t1.bill_ratio = t1.bill_ratio - t2.pay_ratio;

  IF IN_STATE=1 THEN
    SELECT * FROM clients WHERE id IN (SELECT t1.client_id FROM subjects t1, tmp_schedules t2 WHERE t1.id=t2.subject_id AND t2.bill_ratio>0.01);
  ELSE
    SELECT t1.* FROM subjects t1, tmp_schedules t2 WHERE t1.id=t2.subject_id AND t2.bill_ratio>0.01;
  END IF;

END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_getBillManageSheet`(IN `IN_PARA1` INT(2), IN `IN_YEAR` INT(4), IN `IN_MONTH` INT(2), IN `IN_CLIENT_ID` BIGINT(8))
BEGIN 
  DECLARE start_date DATE;
  DECLARE end_date DATE;
  IF IN_PARA1 = 1 THEN
    SELECT
      t1.id AS subject_id,
      t1.process,
      t2.id AS schedule_id, 
      DAY(t2.start_date) AS day,
      t2.start_date AS start_date,
      t1.name AS subject,
      t3.nickname AS client_nick,
      t4.nickname AS subcontract_nick,
      CASE t2.class WHEN 10 THEN '架' ELSE '払' END AS schedule_class, 
      CASE t3.bill_issue WHEN 31 THEN '末' ELSE t3.bill_issue END AS bill_issue,
       CASE t3.bill_payment WHEN 31 THEN '末' ELSE t3.bill_payment END AS bill_payment,
      
      t1.expenses - t1.discount AS amount,
      ROUND(t2.pay_ratio,2) AS pay_ratio,
      ROUND(((t1.expenses - t1.discount)*t2.pay_ratio)/100) AS bill_amount,
      t4.bill_closing pay_closing,
      t4.nickname AS sub_nick,
      t1.amount_subcontract,
      ROUND((t1.amount_subcontract*t2.pay_ratio)/100) AS pay_amount
    FROM 
      subjects t1,
      schedules t2,
      clients t3,
      clients t4 
    WHERE 
      t1.id=t2.subject_id AND 
      t1.client_id=t3.id AND
      t2.subcontract_id=t4.id AND
      YEAR(t2.start_date)=IN_YEAR AND 
      MONTH(t2.start_date)=IN_MONTH
    ORDER BY
      t2.start_date,
      t1.client_id
    ;
  ELSE
      IF IN_YEAR=0 AND IN_MONTH=0 THEN
        SET start_date='1990-1-1';
        SET end_date='2999-12-31';
      ELSE
        SET start_date=DATE_ADD(DATE_ADD(LAST_DAY(CURDATE()), INTERVAL 1 DAY), INTERVAL -1 YEAR);
        SET end_date=LAST_DAY(CURDATE());
      END IF;
      SELECT
      t1.id AS subject_id,
      t1.process,
      t2.id AS schedule_id, 
      t2.start_date AS day,
      t2.start_date AS start_date,
      t1.name AS subject,
      t3.nickname AS client_nick,
      CASE t2.class WHEN 10 THEN '架' ELSE '払' END AS schedule_class, 
      CASE t3.bill_issue WHEN 31 THEN '末' ELSE t3.bill_issue END AS bill_issue,
      t1.expenses - t1.discount AS amount,
      ROUND(t2.pay_ratio,2) AS pay_ratio,
      ROUND(((t1.expenses - t1.discount)*t2.pay_ratio)/100) AS bill_amount,
      t4.bill_closing pay_closing,
      t4.nickname AS subcontract_nick,
      t1.amount_subcontract,
      ROUND((t1.amount_subcontract*t2.pay_ratio)/100) AS pay_amount
    FROM 
      subjects t1,
      schedules t2,
      clients t3,
      clients t4 
    WHERE 
      t1.id=t2.subject_id AND  
      t1.client_id=t3.id AND 
      t2.subcontract_id=t4.id AND 
      t2.start_date >= start_date AND 
      t2.start_date <= end_date AND 
      t1.client_id=IN_CLIENT_ID
    ORDER BY
      t2.start_date DESC
    ;
  END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_getTax`(IN `day` DATE, IN `amount` INT, IN `status` INT, OUT `tax` INT)
    NO SQL
BEGIN
DECLARE rate int;
SET rate = (SELECT tax_rate FROM sys_items WHERE tax_enf = (select max(tax_enf) from sys_items where tax_enf<= day));

IF status=1 THEN
  SET tax = (SELECT ROUND((amount * rate) / 100) AS tax);
ELSEIF status=2 THEN
  SET tax = (SELECT ROUND((amount * rate) / (100 + rate)) AS tax);
ELSE
  SET tax = 0;
END IF;


END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_getTaxTatio`(IN `in_date` DATE)
    NO SQL
BEGIN
DECLARE ratio INT; 
SET ratio=(
SELECT tax_rate FROM sys_items WHERE tax_enf = (select max(tax_enf) from sys_items where tax_enf<= in_date));
SELECT IFNULL(ratio,0);
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_payList`(IN `in_closing_date` VARCHAR(10), IN `in_client_id` BIGINT)
BEGIN

SELECT  DATE_FORMAT(in_closing_date, CONCAT('%Y-%m-',bill_closing))  FROM clients WHERE id = in_client_id;


END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_reCalcTotal`(IN `in_invoice_masters_id` BIGINT, IN `in_invoice_id` BIGINT)
    NO SQL
BEGIN
SET @closing_date=null,@tax_state=0,@yield_amount_total=0,@yield_amount_ratio=0;
SET @discount=0,@adjust_amount=0,@tax_amount=0,@tax=0;
SET @amount_total=0,@subject_id=0;
SELECT 
    @subject_id:=subject_id,
    @closing_date:=closing_date,
    @tax_state:=tax_state,
    @adjust_amount:=adjust_amount 
  FROM 
    invoice_masters 
  WHERE id = in_invoice_masters_id;
SELECT @yield_amount_total:=SUM(yield_amount) FROM invoice_details WHERE invoice_masters_id = in_invoice_masters_id;
SELECT @yield_amount_ratio:= ROUND((@yield_amount_total / expenses) * 10000) FROM subjects WHERE id=@subject_id;
SELECT @discount:=ROUND((discount * @yield_amount_ratio)/10000) FROM subjects WHERE id=@subject_id;
SET @tax_amount:=@yield_amount_total - @discount + @adjust_amount;
CALL p_getTax(@closing_date,@tax_amount,@tax_state,@tax);
UPDATE invoice_masters SET amount=@yield_amount_total,tax=@tax,discount=@discount,discount_ratio=@yield_amount_ratio WHERE id = in_invoice_masters_id;
SELECT @amount_total:=SUM(amount-discount+adjust_amount+CASE tax_state WHEN 1 THEN tax ELSE 0 END) FROM invoice_masters WHERE invoices_id=in_invoice_id; 
UPDATE invoices SET amount=@amount_total WHERE id=in_invoice_id; 
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `p_test`(IN `IN_STATE` INT, IN `IN_DATE` VARCHAR(10))
    NO SQL
BEGIN

  DECLARE w_subject_id BIGINT;
  DECLARE w_bill_closing INT;
  DECLARE w_pay_ratio DECIMAL(7,4);

  CREATE TEMPORARY TABLE tmp_invoice_masters AS SELECT subject_id,SUM(ratio) AS pay_ratio FROM invoice_masters GROUP BY subject_id; 


  CREATE TEMPORARY TABLE tmp_schedules AS SELECT subject_id,SUM(pay_ratio) AS bill_ratio FROM schedules WHERE start_date<=IN_DATE GROUP BY subject_id; 

  UPDATE tmp_schedules AS t1 INNER JOIN tmp_invoice_masters t2 ON t1.subject_id=t2.subject_id SET t1.bill_ratio = t1.bill_ratio - t2.pay_ratio;

  IF IN_STATE=1 THEN
SELECT * FROM clients WHERE id IN (SELECT t1.client_id FROM subjects t1, tmp_schedules t2 WHERE t1.id=t2.subject_id AND t2.bill_ratio>0);
  ELSE
    SELECT t1.* FROM subjects t1, tmp_schedules t2 WHERE t1.id=t2.subject_id AND t2.bill_ratio>0;
  END IF;







END$$
DELIMITER ;
