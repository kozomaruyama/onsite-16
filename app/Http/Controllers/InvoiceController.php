<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\v_invoice;
use App\Models\v_invoice_master;
use App\Models\Invoice_master;
use App\Models\Invoice_detail;
use App\Models\v_invoice_detail;
use App\Models\Task;
use App\Models\Client;
use App\Models\v_subject;

use App\Library\CommLib;

class InvoiceController extends Controller
{

	public function __construct()
	{
			$this->middleware('auth');
	}

// ----------------------------------------------------------------------------
//
// 請求書情報の取得（全件）
//
// ----------------------------------------------------------------------------
public function index()
{

    $invoices=v_invoice::get();

    $clients=Client::get();

    $calendars=DB::table('invoices')
        ->selectRaw('year(closing_date) AS year')
        ->selectRaw('month(closing_date) AS month')
        ->groupBy('year','month')
        ->get();
    return view('invoice',compact('invoices','clients','calendars'));


}

public function read($id) {

    $invoice = v_invoice::find($id);
    $invoice_masters = v_invoice_master::where('invoice_id',$id)->orderBy('orderNo','asc')->get();
		// $count = v_invoice::where('client_id',$invoice['client_id'])->where('id','>',$id)->count();
		// $count = v_invoice::where('client_id',$invoice['client_id'])->where('closing_date','>',$invoice->closing_date)->count();
		// $status = $count == 0;
		// $max = v_invoice::max('id')->where('client_id',$invoice['client_id'])->first();
		$max = v_invoice::select(DB::raw('max(id) AS max'))->where('client_id',$invoice['client_id'])->first();
		$status = $max['max'] == $id;
		return ['invoice'=>$invoice,'invoice_masters'=>$invoice_masters,'status'=>$status];

}
// ----------------------------------------------------------------------------
//
// 請求書情報の取得（抽出）
//
// ----------------------------------------------------------------------------
public function extract(Request $request)
{

    if ($request->category == "date") {
        $start = date($request->key . '-1');
        $end = date('Y-m-d',strtotime($request->key . ' last day of this month'));
        $details=v_invoice::
			whereBetween('closing_date',[$start, $end])->
			orderBy('closing_date','desc')->
			orderBy('id','desc')->
			get();
    } else if ($request->category == "client") {
        $details=v_invoice::where('client_id',$request->key)->orderBy('id','desc')->get();
    } else {
        $details=v_invoice::orderBy('id','desc')->get();
    };

    return $details;

}

// ----------------------------------------------------------------------------
//
// 請求書情報の取得（sql）
//
// ----------------------------------------------------------------------------
public function sql(Request $request)
{
// file_put_contents("request.txt",  var_export("aaaa", true));
    // return DB::select('SELECT * FROM v_invoices');
    return DB::select( $request->sql);
    // return  ['result' => true, 'category' => 'XXX', 'count' => 10 ];
    // return mb_convert_encoding($request->sql, "UTF-8", "SJIS-win");
    // return "hello";


}

// ----------------------------------------------------------------------------
//
// 請求書情報のグループ編集
//
// ----------------------------------------------------------------------------
	public function group1(Request $request) {

		$targetItems = $request->targetItems;

		$invoices = invoice::select(['i.*','c.name AS client_name',])
				->from('invoices AS i')
				->join('clients AS c', 'c.id', '=', 'i.client_id')
				->whereIn('i.id', $targetItems)
				->get();
// file_put_contents("invoices.txt",  var_export($invoices, true));
// file_put_contents("subjects.txt",  var_export($invoices[0]->v_invoice_masters, true));
		foreach ($invoices as $invoice){
			$master_no = 0;
			foreach ($invoice->v_invoice_masters as $master){
				++$master_no;
				$master["no"] = $master_no;
// file_put_contents("v_invoice_details.txt",  var_export($master->v_invoice_details, true));
				$detail_no = 0;
				foreach ($master->v_invoice_details as $detail){
					if ($detail["isLabel"] == "0") {
						++$detail_no;
						$detail["no"] = $detail_no;
					}  else {
						$detail["no"] = "";
					}
				}
			}
		}
		return $invoices;

	}

	public function group(Request $request) {

		$invoice_id = $request->targetItems[0];

		$invoice = v_invoice::
			find($invoice_id);
// return $invoice['id'];	

		$raw='
			min(id) AS id,
			min(subject_id) AS subject_id,
			min(schedule_id) AS schedule_id,
			min(name) AS name,
			min(breakdown) AS breakdown,
			min(site_name) AS site_name,
			min(expenses) AS expenses,
			GROUP_CONCAT(memo) AS memo,
			sum(amount) AS amount,
			min(tax_state) AS tax_state,
			tax,
			sum((CASE WHEN tax_state=1 THEN tax ELSE 0 END)) AS tax1,
			sum((CASE WHEN tax_state=2 THEN tax ELSE 0 END)) AS tax2,
			sum((CASE WHEN tax_state=3 THEN tax ELSE 0 END)) AS tax3,
			sum(discount) AS discount,
			min(discount_total) AS discount_total,
			ROUND(sum(ratio),2) AS ratio,
			sum(bill_amount) AS bill_amount,
			sum(adjust_amount) AS adjust_amount,
			min(adjust_title) AS adjust_title,
			min(site_address) AS site_address,
			min(code) AS code
		';
		// $raw='
		// 	id,
		// 	subject_id,
		// 	schedule_id,
		// 	name,
		// 	breakdown,
		// 	site_name,
		// 	expenses,
		// 	memo,
		// 	amount,
		// 	tax_state,
		// 	tax,
		// 	CASE WHEN tax_state=1 THEN tax ELSE 0 END AS tax1,
		// 	CASE WHEN tax_state=2 THEN tax ELSE 0 END AS tax2,
		// 	CASE WHEN tax_state=3 THEN tax ELSE 0 END AS tax3,
		// 	discount,
		// 	discount_total,
		// 	ROUND(ratio,2) AS ratio,
		// 	bill_amount,
		// 	adjust_amount,
		// 	adjust_title,
		// 	site_address,
		// 	code
		// ';
		$masters = v_invoice_master::
				select(DB::raw($raw))->
				where('invoice_id',$invoice['id'])->
				groupBy('subject_id')->
				// orderByRaw('start_date ASC, orderNo ASC')->
				orderBy('id')->
				get();
		$master_no = 0;
		foreach ($masters as $master){
			++$master_no;	
			$master["no"] = $master_no;
			$raw="
				min(name) AS name,
				min(breakdown) AS breakdown,
				min(volume) AS volume,
				min(unit) AS unit,
				min(unit_name) AS unit_name,
				min(cost) AS cost,
				min(amount) AS amount,
				min(remain_ratio) AS remain_ratio,
				min(memo) AS memo,
				min(isLabel) AS isLabel,
				sum(yield_ratio) AS yield_ratio,
				sum(yield_amount) AS yield_amount
			";
			$details = v_invoice_detail::
				select(DB::raw($raw))->
				where('invoice_id',$invoice['id'])->
				where('subject_id',$master['subject_id'])->
				groupBy('task_id')->
				orderBy('orderNo')->
				get();
			$detail_no = 0;
			foreach ($details as $detail){
				if ($detail["isLabel"]==0) {
					++$detail_no;	
					$detail["no"] = $detail_no;
				} else {
					$detail["no"] = 0;
				}
			}	
			$master['details'] = $details;			
		}	
		// $invoice['v_invoice_masters'] = $masters;

// return $invoice;				
		$raw="
			min(name) AS name,
			min(breakdown) AS breakdown,
			min(volume) AS volume,
			min(unit) AS unit,
			min(unit_name) AS unit_name,
			min(cost) AS cost,
			min(amount) AS amount,
			min(remain_ratio) AS remain_ratio,
			min(memo) AS memo,
			min(isLabel) AS isLabel,
			sum(yield_ratio) AS yield_ratio,
			sum(yield_amount) AS yield_amount
		";
		$details = v_invoice_detail::
				select(DB::raw($raw))->
				where('invoice_id',$invoice['id'])->
				groupBy('task_id')->
				orderBy('orderNo')->
				get();
		$detail_no = 0;
		foreach ($details as $detail){
			++$detail_no;	
			$detail["no"] = $detail_no;
		}	
		// $invoice['v_invoice_masters']['v_invoice_details'] = $details;

		// return $masters;
		return ['invoice'=>$invoice,'masters'=>$masters,'details'=>$details,];

	}

// ----------------------------------------------------------------------------
//
// 請求書の締め処理（ストアードプロシージャ―）
//
// ----------------------------------------------------------------------------
	// public function close(Request $request)
	// {

	// 	$clients = $request->clients;
	// 	$date = null;

    //     DB::beginTransaction();
    //     try {
    //        foreach ($clients as $client){
	// 			$date = $request->date . '-' . $client['bill_closing'];
	// 			$result = DB::statement("CALL p_closing(" . $client['id'] . ",'" . $date . "',@result)");
    //         }
    //         DB::commit();
    //     } catch (\PDOException $e){
	// 		DB::rollBack();
	// 		$result = null;
    //     }

	// 	return $result;
	// }

// ----------------------------------------------------------------------------
//
// 請求書の締め処理
//
// ----------------------------------------------------------------------------

	// public function closing(Request $request) {
	// 	$clients = $request->clients;
	// 	$subjects = $request->subjects;
  //       $firstId = 0;
	// 	DB::beginTransaction();
  //       try {
	// 		foreach ($clients as $client){
	// 			$amount_total = 0;
	// 			$billAmount_total = 0;
	// 			$tax_1 = 0;
	// 			$tax_2 = 0;
	// 			$date = $request->date['year'] . '-'  . $request->date['month'] . '-' . $client['bill_closing'];
	// 			Invoice::insert([
	// 				'client_id' => $client['id'],
	// 				'closing_date' => $date,
	// 				'tax_state' => $client['tax_state'],
	// 			]);
	// 			// 追加された請求情報のidを取得
	// 			$invoice_id = DB::getPdo()->lastInsertId();
	// 			if ($firstId==0) { $firstId = $invoice_id; }
	// 			$orderNo = 0;
	// 			$client_id= $client['id'];
	// 			// $subjectArray = array_filter($subjects, function($subject,$client_id){
	// 			//     return $subject['client_id'] == $client_id;
	// 			// });
	// 			$subjectArray = array_filter($subjects, function($subject) use($client_id){
	// 					return $subject['client_id'] == $client_id;
	// 			});
	// 			foreach ($subjectArray as $subject){
	// 				$masters = Invoice_master::where('subject_id', $subject['id'])->get();
	// 				if (count($masters) == 0) {
	// 					$ratio = $subject['pay_ratio'];
	// 					$amount = round(($subject['expenses'] *  $ratio) / 100);
	// 				} else {
	// 					$pay_amount = Invoice_master::where('subject_id', $subject['id'])->sum('amount');
	// 					$adjust_amount = Invoice_master::where('subject_id', $subject['id'])->sum('adjust_amount');
	// 					$amount = $subject['expenses'] - ($pay_amount+$adjust_amount);
	// 					$ratio = round(($amount / $subject['expenses']) * 10000) / 100;
	// 				}
	// 				$discount = round(($subject['discount'] * $ratio) / 100);
	// 				$bill_amount = $amount - $discount;
	// 				$tax = CommLib::tax($bill_amount,$subject['tax_state']);
	// 				if ($subject['tax_state']==1) {
	// 					$tax_1 += $tax;
	// 				} else if ($subject['tax_state']==2) {
	// 					$bill_amount += $tax;
	// 					$tax_2 += $tax;
	// 				}
	// 				if ($bill_amount>0) {
	// 					Invoice_master::insert([
	// 						'invoice_id' => $invoice_id,
	// 						'subject_id' => $subject['id'],
	// 						'closing_date' => $date,
	// 						'tax_state' => $subject['tax_state'],
	// 						'amount' => $amount,
	// 						'tax' => $tax,
	// 						'discount' => $discount,
	// 						'ratio' => $ratio,
	// 						'adjust_amount' => 0,
	// 						'bill_amount' => $bill_amount,
	// 						'orderNo' => ++$orderNo,
	// 					]);
	// 					// 追加された請求情報のidを取得
	// 					$master_id = DB::getPdo()->lastInsertId();
	// 					$detail_ratio = 0;
	// 					$tasks = Task::where('subject_id', $subject['id'])->get();
	// 					foreach ($tasks as $task){
	// 						$details = Invoice_detail::where('task_id', $task['id'])->get();
	// 						if (count($details) == 0) {
	// 							$detail_amount = round(($task['amount'] * $ratio) / 100);
	// 							$detail_ratio = $ratio;
	// 							// if ($detail_ratio>0 && $detail_ratio<100) {
	// 							//     $memo = "残" . (100-$detail_ratio) . "%";
	// 							// } else {
	// 							//     $memo = "";
	// 							// }
	// 						} else {
	// 							$pay_amount = Invoice_detail::where('task_id', $task['id'])->sum('yield_amount');
	// 							$detail_amount = $task['amount'] - $pay_amount;
	// 							if ($detail_amount==0) {
	// 								$detail_ratio= 0;
	// 						//     $memo="済";
	// 							} else {
	// 								$detail_ratio = round(($detail_amount / $task['amount']) * 10000) / 100;
	// 							//     $memo = "残" . $detail_ratio ."%";
	// 							}
	// 						}
	// 						Invoice_detail::insert([
	// 							'invoice_master_id' => $master_id,
	// 							'task_id' =>  $task['id'],
	// 							'yield_ratio' => $detail_ratio,
	// 							'yield_amount' => $detail_amount ,
	// 							// 'memo' =>  $memo,
	// 						]);
	// 					}
	// 					$amount_total += $amount;
	// 					$billAmount_total += $bill_amount;
	// 				}
	// 			}
	// 			if ($amount_total>0) {
	// 				$pay_amount = Invoice_master::where('subject_id', $subject['id'])->sum('bill_amount');
	// 				$adjust_amount = $subject['bill_amount'] - $pay_amount;
	// 				if (abs($adjust_amount)>9) {
	// 					$adjust_amount = 0;
	// 				}
	// 				if ($billAmount_total)
	// 				Invoice::where('id',$invoice_id)->update([
	// 					'bill_amount'=> $billAmount_total + $adjust_amount,
	// 					'amount'=> $amount_total,
	// 					'adjust_amount'=>$adjust_amount,
	// 					'tax_1'=> $tax_1,
	// 					'tax_2'=> $tax_2,
	// 				]);
	// 			} else {
	// 				Invoice::where('id',$invoice_id)->delete();
	// 			}
	// 		}
	// 		DB::commit();
	// 		$newVal =  v_invoice::where('id','>=', $firstId)->get();
	// 		return ['newVal'=>$newVal,'date'=>$request->date];
	// 	} catch (\PDOException $e){
	// 		DB::rollBack();
	// 		return null;
	// 	}

	// }

// ----------------------------------------------------------------------------
//
// 請求情報の更新
//
// ----------------------------------------------------------------------------

	public function update(Request $request, Invoice $invoice)     {
			$invoice->update($request->all());
			return $invoice;
	}

	public function update2(Request $request) {
		$reqInvoice = $request->invoice;
			$reqMasters =  $request->invoice_masters;
		DB::beginTransaction();
		try {
			$invoice = Invoice::where('id', $reqInvoice['id'])->first();
			$invoice->client_id = $reqInvoice['client_id'];
			$invoice->code = $reqInvoice['code'];
			$invoice->name = $reqInvoice['name'];
			$invoice->closing_date = $reqInvoice['closing_date'];
			$invoice->issue_date = $reqInvoice['issue_date'];
			$invoice->payment_date = $reqInvoice['payment_date'];
			$invoice->amount = $reqInvoice['amount'];
			$invoice->tax_state = $reqInvoice['tax_state'];
			$invoice->tax_1 = $reqInvoice['tax_1'];
			$invoice->tax_2 = $reqInvoice['tax_2'];
			$invoice->status = $reqInvoice['status'];
			$invoice->exec_log_id = $reqInvoice['exec_log_id'];
			$invoice->save();
			foreach ($reqMasters as $reqMaster){
				$master = Invoice_master::where('id', $reqMaster['id'])->first();
				if ($master['ratio'] != $reqMaster['ratio']) {
				//
					$total = 0;
					$details = Invoice_detail::select(['d.*','t.amount',])
									->from('invoice_details AS d')
									->join('tasks AS t', 't.id', '=', 'd.task_id')
									->where('d.invoice_master_id', $master['id'])
									->get();
					foreach ($details as $detail){
						$amount = round(($detail['amount'] * $reqMaster['ratio']) / 100);
						$total += $amount;
						Invoice_detail::where('id', $detail['id'])
							->update([
								'yield_ratio'=>$reqMaster['ratio'],
								'yield_amount'=>$amount,
							]
						);
					}
					$discount = round(($reqMaster['discount'] * $reqMaster['ratio']) / 100);
					$master->amount = $total;
					$master->discount = $discount;
					$master->ratio = $reqMaster['ratio'];
					$master->bill_amount = $total - $discount;
					$master->save();
				}
			}
			DB::commit();
		} catch (\PDOException $e){
			DB::rollBack();
		}
		return $request;
	}

	// 削除
	public function destroy($id)
	{

		try {
			Invoice::destroy($id);
			return ['status'=>true];
		} catch(\Throwable $e) {
			return ['status'=>false, 'value'=>$e];
		}

	}

}
