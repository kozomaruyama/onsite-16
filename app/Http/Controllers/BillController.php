<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\Subject;
use App\Models\Invoice;
use App\Models\Pay;
use App\Models\Invoice_master;
use App\Models\v_invoice_master;
use App\Models\Invoice_detail;
use App\Models\v_invoice_detail;
use App\Models\Task;
use App\Models\Schedule;
use App\Models\v_schedule_detail;
use App\Models\v_schedule;
use App\Models\v_bill_client;
use DateTime;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;

use App\Library\CommLib;

class BillController extends Controller
{

	public function __construct()
	{
			$this->middleware('auth');
	}


  // 読込
	public function index() {

		$clients=Client::where("class","<",10)->get();
		$subcontracters=Client::whereBetween("class",[11,19])->get();
		$calendars=v_schedule::select('closing')
			->where('status','=',1)
			->where('percentage','>',0)
			->groupBy('closing')
			->get();

		$invoice_calendars=DB::table('invoices')
			->selectRaw('year(closing_date) AS year')
			->selectRaw('month(closing_date) AS month')
			->groupBy('year','month')
			->get();

		$bill_calendars=DB::table('bills')
			->selectRaw('year(pay_date) AS year')
			->selectRaw('month(pay_date) AS month')
			->groupBy('year','month')
			->get();
			

		$user = Auth::user();
		$role = $user['role'];
		$person = Person::find($user['person_id']);
		return view('bill', compact('clients','subcontracters','calendars','invoice_calendars','bill_calendars','role','person'));    
	}
	
	public function catrgry_ym($ym) {

		$invoice_calendars=DB::table('invoices')
			->selectRaw('year(closing_date) AS year')
			->selectRaw('month(closing_date) AS month')
			->groupBy('year','month')
			->get();
		return $invoice_calendars;
		    
	}

	public function all($ym) {


		$result = DB::select("CALL p_getBillClientList(1,'" . date("Y-m-d") . "')");
		return $result;

		// $rows = v_schedule::select('client_id as id','client','main_nick AS nickname','bill_issue',DB::raw('sum(pay_ratio) AS pay_ratio'))
		// 	->whereNotNull('closing')
		// 	->where('closing', '<=',$ym)
		// 	->where('percentage', '>',0)
		// 	->groupBy('client_id','client','main_nick','bill_issue')
		// 	->get();

		// $rows2=[];
		// foreach($rows as $key => $row) {
		// 	$pay_ratio_total = v_invoice_master::where('client_id',$row['id'])->sum('ratio');
		// 	$ratio = $row['pay_ratio'];
		// 	if ($ratio>$pay_ratio_total) {
		// 		$row['pay_ratio']=$ratio - $pay_ratio_total;
		// 		if ($row['pay_ratio']>0.001) {
		// 			array_push($rows2,$row);
		// 		}
		// 	}
		// }
		// return $rows2;		
	}

	public function opt($ym) {
		$rows = v_schedule::select('client_id as id','client','main_nick','bill_issue','pay_ratio')
			->whereNotNull('closing')
			->where('closing', '<=',$ym)
			->where('status','=',1)
			->where('pay_ratio', 0)
			->where('class',DB::raw('pay_status'))
			->groupBy('client_id','client','main_nick','bill_issue')
			->get();
		return $rows;		
	}

	public function calendar($ym) {
		$rows = v_schedule::select('client_id as id','client','main_nick','bill_issue')
					->where('closing', '<=',$ym)
					->where('status', '<',6)
					->groupBy('client_id','client','main_nick','bill_issue')
					->get();
		return $rows;
	}

	public function client($id) {    
		$closingDays = v_schedule::select('id','closing','client as name','main_nick','bill_issue','status' )
								->where('client_id','=', $id)
								->groupBy('closing','client','main_nick','bill_issue')
								->orderBy('closing','desc')
								->get();     

		$raw="id,closing,client as name,main_nick,bill_issue,
						CASE
							WHEN closing > date_format(CURDATE(),'%Y-%m') THEN 8
							WHEN MAX(status)=MIN(status) THEN status 
							ELSE 9 END as status";

		$closingDays = v_schedule::select(DB::raw($raw))
			->where('client_id','=', $id)
			->groupBy('closing','client','main_nick','bill_issue')
			->orderBy('closing','desc')
			->get();     

		return $closingDays;
	}

	public function details(Request $request) {
		$client_id = $request->id;
		$ym = $request->ym;

		// $raw="MAX(id) as id,
		// 			class,
		// 			subject_id,
		// 			MIN(start_date) as start_date,
		// 			MAX(end_date) as end_date,
		// 			closing,
		// 			MAX(day) as day,
		// 			status,
		// 			name,
		// 			main_nick,
		// 			expenses,
		// 			discount,
		// 			bill_amount,
		// 			tax_state,
		// 			schedule_class,
		// 			pay_status,
		// 			pay_ratio,
		// 			pay_ratio2,
					
		// 			";
		// 	$rows = v_schedule::select(DB::raw($raw))
		// 				->where('client_id',$client_id)
		// 				->whereNotNull('closing')
		// 				->where('closing','<=', $ym)
		// 				->where('percentage','>', 0)
		// 				->groupBy('class','subject_id','closing','','status','name','main_nick','expenses','discount','bill_amount','tax_state','schedule_class','pay_ratio','pay_ratio2','pay_status')
		// 				->get();

		$raw="*
					";
		$rows = v_schedule::select(DB::raw($raw))
					->where('client_id',$client_id)
					->whereNotNull('closing')
					->where('closing','<=', $ym)
					// ->where('percentage','>', 0)
					->where('pay_ratio','>', 0)
					->orderBy('start_date')
					->get();

		$rows2=[];
		foreach($rows as $key => $row) {
			$pay_ratio_total = Invoice_master::where('schedule_id',$row['id'])->sum('ratio');
			// return $pay_ratio_total;  
			$ratio = ($row['pay_ratio'] * $row['percentage']) / 100;
			// $ratio = $row['percentage'];
			$ratio = $row['pay_ratio'];
			if ($ratio>$pay_ratio_total) {
				$row['pay_ratio']=round($ratio - $pay_ratio_total,2);
				if ($row['pay_ratio']>0.01) {
					array_push($rows2,$row);
				}
			}
		}

		return $rows2;    
		
	}

	// -----------------------------------------------------------------------------
	// 
	//	締め処理 
	// 
	// -----------------------------------------------------------------------------
	public function close(Request $request) {

		$client = Client::find($request->client_id);
		
		$closings = array_column($request->values, 'closing' );
		$closing = max($closings);

		if ($client['bill_closing']>=28) {
		    $start = date('Y-m-d' , strtotime($closing . '-01'));
				$end = date('Y-m-t' , strtotime($closing . '-01'));
		} else {
		    $end = date('Y-m-d' , strtotime($closing . '-' . $client['bill_closing']));
		    $start = date('Y-m-d' , strtotime("$end -1 month"));
		    $start = date('Y-m-d' , strtotime("$start +1 day"));
		}

		if ($client['bill_issue']>=28) {
			$date = date('Y-m-d' , strtotime($closing . '-01'));
			$issue_date = date('Y-m-t' , strtotime($date . '-01'));
		} else {
			$issue_date = date('Y-m-d' , strtotime($closing . '-' . $client['bill_issue']));
		}
		if ($end>$issue_date) {
			$issue_date = date('Y-m-d' ,strtotime("$issue_date +1 month"));
		}

		if ($client['bill_payment']>=28) {
			$date = date('Y-m-d' , strtotime($closing . '-01'));
			$date = date('Y-m-d' , strtotime("$date ". $client['bill_payment_class']   . " month"));
			$payment_date = date('Y-m-d' , strtotime("$date -1 day"));
		} else {
			$payment_date = date('Y-m-d' , strtotime($closing . '-' . $client['bill_payment']));
			if ($client['bill_payment_class']>1) {
				$num = (int)$client['bill_payment_class'] - 1;
				$payment_date = date('Y-m-d' , strtotime("$payment_date " . $num . " month"));
			}
		}

		$payment_date = CommLib::workDay($payment_date,1);

		$orderNo = 0;
		$billAmount_total = 0;
		$invoice_total = 0;
		$discount_total = 0;
		$tax_1 = 0;
		$tax_2 = 0;
		$tax_3 = 0;
		$taxAmount_1 = 0;
		$taxAmount_2 = 0;

		// トランザクションの開始
		DB::beginTransaction();
		try {

			// 請求情報を作成
			Invoice::insert([
				'client_id' => $client['id'],
				'closing_date' => $end,
				'issue_date' => $issue_date,
				'payment_date' => $payment_date,
				'tax_state' => $client['tax_state'],
			]);
			// 追加された請求情報のidを取得
			$invoice_id = DB::getPdo()->lastInsertId();

			foreach ($request->values as $value) {
				$ratio=$value['pay_ratio'];
				// 請求マスターの支払済みレートを集計する
				$ratio_total=Invoice_master::where('subject_id', $value['subject_id'])->sum('ratio');
				// 支払済みレートが１００％に達しているかを判定
				if ($ratio_total  < 100) {	
					// 請求マスターを作成
					Invoice_master::insert([
						'invoice_id' => $invoice_id,
						'subject_id' => $value['subject_id'],
						'schedule_id' => $value['id'],
						'closing_date' => $end,
						'tax_state' => $value['tax_state'],
						'adjust_amount' => 0,
						'orderNo' => ++$orderNo,
					]);
					// 追加された請求情報のidを取得
					$master_id = DB::getPdo()->lastInsertId();
					// 案件に関連するタスク情報の取得
					$tasks = Task::where('subject_id', $value['subject_id'])->get();
					
					$master_total = 0;
					$bill_amount = 0;
					$remain1 = 0;
					$remain2 = 0;

					if (count($tasks)>0) {	
						foreach ($tasks as $task){
							$detail = v_schedule_detail::where('class',$value['class'])->where('task_id',$task['id'])->first();
							if (isset($detail)) {
								$remain1 = v_invoice_detail::where('task_id',$detail['task_id'])->where('details_class',$detail['class'])->sum('yield_ratio');
								$remain2 = v_invoice_detail::where('task_id',$detail['task_id'])->sum('yield_ratio');
								$ratio = round((($detail['percentage']*$value['master_pay_ratio'])/100),2) - $remain1 ;
								$amount = round(($detail['amount'] * $ratio) / 100);
								$remain_ratio = 100 - ($remain2 + $ratio);
							} else {
								$amount=0;
								$remain_ratio=0;
							}
							Invoice_detail::insert([
								'class' => $value['class'],
								'invoice_master_id' => $master_id,
								'task_id' =>  $task['id'],
								'yield_ratio' => $ratio,
								'yield_amount' => $amount,
								'remain_ratio' => $remain_ratio,
								// 'memo' => $task['memo'],
								'memo' => "残:" . $remain_ratio,
							]);
							$master_total += $amount;
						}

						$ratio = round(($master_total / $value['expenses']) * 100,2);
	
					} else {
						$ratio= $value['pay_ratio'] ;
						$master_total = round(($value['expenses'] * $ratio) / 100);
					}
					
					$discount = round(($value['discount'] * $ratio) / 100);
					$bill_amount = $master_total - $discount;
					$tax = CommLib::tax($bill_amount,$value['tax_state']);

					if ($value['tax_state']==1) {
						$taxAmount_1 += $bill_amount;
						$tax_1 += $tax;
					} else if ($value['tax_state']==2) {
						$taxAmount_2 += $bill_amount;
						$bill_amount += $tax;
						$tax_2 += $tax;							
					} else if ($value['tax_state']==3) {
						$bill_amount += $tax;
						$tax_3 += $tax;							
					}		
					$memo =  $value['class'] == 10 ? "組立" : "解体";
					$memo = substr($value['start_date'],5) . $memo;
					Invoice_master::find($master_id)->update([
						'amount'=>$master_total, 
						'discount'=>$discount, 
						'ratio' => $ratio,
						'bill_amount' => $bill_amount,
						'tax' => $tax,
						'memo' => $memo ,
					]);	

					$subcontracter = Client::find($value['subcontract_id']);

					$closing = date('Y-m', strtotime($value['end_date']));
				
					if ($subcontracter['bill_closing']>=28) {
						$closing_date = date('Y-m-t', strtotime($closing . '-01'));
					} else {
						$closing_date = date('Y-m-d',strtotime($closing . '-' . $subcontracter['bill_closing']));
						if (date('d',strtotime($value['end_date'])) > $subcontracter['bill_closing']) {
							$closing_date = date('Y-m-d',strtotime("$closing_date 1 month" ));
						}	
					}

					$num = (int)$subcontracter['bill_payment_class'] ;
					// if (date('d',strtotime($value['end_date'])) > $subcontracter['bill_closing']) {
					// 	++$num;
					// }						
					if ($subcontracter['bill_payment']>=28) {
						$payment_date=date('Y-m-t' , strtotime($closing . '-01' . " $num month"));
					} else {
						$num = $num - 1;
						$payment_date=date('Y-m-d' , strtotime($closing . "-" . $subcontracter['bill_payment'] . " $num month"));
					}

					Pay::insert([
						'class' =>$value['class'],
						'invoice_id' => $invoice_id,
						'subject_id' => $value['subject_id'],
						'schedule_id' => $value['id'],
						'client_id' => $value['subcontract_id'],
						'start_date' => $value['start_date'],
						'end_date' =>$value['end_date'],
						'closing_date'=> $closing_date,
						'pay_date' => $payment_date,
						'name' =>  $value['name'] . "(" . $value['main_nick'] . ")",
						'volume' => $ratio,
						'cost' => $value['amount_subcontract'],
						'amount' => round(($value['amount_subcontract'] * $ratio) / 100) 
					]);

					// 案件情報の支払率を判定し、スケジュール情報のステータスを更新
					if ($value['pay_ratio']>0) {
						// 案件情報の支払率が０より大きい場合
						// 案件情報の支払率を判定
						if ($value['pay_ratio']==100) {
							// 案件情報の支払率が100の場合
							// スケジュール情報で案件ＩＤが該当案件のＩＤと等しいレコードのステータスを９にする
							Schedule::where('subject_id',$value['subject_id'])->update(['status' => 9]);
						} else {
							// 案件情報の支払率が100でないの場合
							// スケジュール情報で該当スケジュールのＩＤと等しいレコードのステータスを９にする
							Schedule::find($value['id'])->update(['status' => 9]);
						}
					} else {
						// 案件情報の支払率が０の場合
						// スケジュール情報で該当スケジュールのＩＤと等しいレコードのステータスを２にする
						Schedule::find($value['id'])->update(['status' => 2]);
					}

					$discount_total += $discount;
					// 請求マスターの金額を集計する
					$invoice_total += $master_total;
					// 請求マスターの請求金額を集計する
					$billAmount_total += $bill_amount;
				} else {
					Schedule::where('subject_id', $value['subject_id'])->update(['status' => 9]);
				}

				Subject::find($value['subject_id'])->update(['status' => 30]);

			}

			$adjust_amount = 0;

			Invoice::find($invoice_id)->update([
				'bill_amount'=> $billAmount_total + $adjust_amount ,
				'amount'=> $invoice_total,
				'discount'=> $discount_total,
				'adjust_amount'=>$adjust_amount,
				'tax_1'=> $tax_1,
				'tax_2'=> $tax_2,
				'taxAmount_1'=> $taxAmount_1,
				'taxAmount_2'=> $taxAmount_2,
			]);

			// トランザクションをコミット
			DB::commit();
			// 締め切り年月をリターン 
			return $closing ;

		} catch (\PDOException $e){
			// トランザクションをロールバック
			DB::rollBack();
			// エラー情報をリターン
			return $e;
		}
	}
		
}

