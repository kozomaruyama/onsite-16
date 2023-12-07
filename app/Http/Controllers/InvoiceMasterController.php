<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Invoice_master;
use App\Models\Invoice_detail;
use App\Models\v_invoice_master;
use App\Models\Pay;
use App\Models\v_invoice_detail;
use App\Models\v_invoice;
use App\Models\Schedule;

use App\Library\CommLib;

class InvoiceMasterController extends Controller
{

    public function __construct()
	{
        $this->middleware('auth');
	}
    
	public function index($invoiceId)
	{

		// $raw="
        //         id,
        //         invoice_id,
        //         subject_id,
        //         name,
        //         breakdown,
        //         sum(amount) as amount,
        //         MIN(subject_bill_amount) as subject_bill_amount,
        //         ROUND(SUM(ratio),2) as ratio,
        //         SUM(tax) as tax,
        //         SUM(bill_amount) as bill_amount,
        //         memo,
        //         adjust_amount
        //     ";
		$raw="
                id,
                invoice_id,
                subject_id,
                name,
                breakdown,
                amount,
                subject_bill_amount,
                ROUND(ratio,2) as ratio,
                tax,
                bill_amount,
                memo,
                adjust_amount
            ";

        $masters = v_invoice_master::
                select(DB::raw($raw))->
                with('v_invoice_details')->
                where('invoice_id', $invoiceId)->
                // groupBy('subject_id')->
                orderByRaw('orderNo ASC, id DESC')->
                get();

		return $masters;
	}

	public function read($id)
	{

        $master = v_invoice_master::with('v_invoice_details')->find($id);
        $subject = Subject::find($master['subject_id']);
        $billl_total = Invoice_master::where('subject_id',$master['subject_id'])->sum('bill_amount');
        $fraction_total = Invoice_master::where('id','!=',$id)->where('subject_id',$master['subject_id'])->sum('fraction');
        return ['master'=>$master,'subject'=>$subject,'billl_total'=>$billl_total,'fraction_total'=>$fraction_total];

	}

	public function show($id)
	{
        // $master = Invoice_master::find($id)->get();
        $master = Invoice_master::with(['subject','v_invoice_details'])->find($id);
        // $master = Invoice_master::with('invoice_details')->find($id);
        // file_put_contents("v_invoice_details.txt",  var_export($master->v_invoice_details, true));
        // file_put_contents("invoice_details.txt",  var_export($master->invoice_details, true));
        // $sql = "SELECT * FROM invoice_masters WHERE id = $id";
        // $master = DB::select($sql);
		return $master;
	}

    public function group(Request $request) {

        $targetItems = $request->targetItems;

        $masters = [];
        foreach ($targetItems as $item) {
            $master_items = invoice_master::select(['m.*','s.name AS subject_name',])
                    ->from('invoice_masters AS m')
                    ->join('subjects AS s', 's.id', '=', 'm.subject_id')
                    ->where('m.invoice_id', $item)
                    ->get();
            $details = [];
            foreach ($master_items as $master_item) {
                $details[] = Invoice_detail::select(['d.*','t.name AS task_name',])
                        ->from('invoice_details AS d')
                        ->join('tasks AS t', 't.id', '=', 'd.task_id')
                        ->where('d.invoice_master_id', $master_item['id'])
                        ->get();
                $master_item[] = $details;
            }
            $masters[] = $master_items;
        }
        file_put_contents("masters.txt",  var_export($masters, true));




        // $masters = invoice_master::select(['m.*','s.name AS subject_name',])
        //             ->from('invoice_masters AS m')
        //             ->join('subjects AS s', 's.id', '=', 'm.subject_id')
        //             ->whereIn('m.invoice_id', $targetItems)
        //             ->get();

        // foreach ($masters as $master){
        //     $no = 0;
        //     foreach ($master->details as $detail){
        //         if ($task["isLabel"] == "0") {
        //             ++$no;
        //             $task["no"] = $no;
        //         }  else {
        //             $task["no"] = "";
        //         }
        //     }
        //     file_put_contents("no.txt",  var_export($no, true));
        // }
        // return $masters;
    }



    public function print(Request $request)
    {
        if ($request->ids!=NULL) {
            $masters = v_invoice_master::whereIn('id',$request->ids)->with('v_invoice_details')->get();
        } else {
            $masters = v_invoice_master::with('v_invoice_details')->get();
        }
        foreach ($masters as $master){
            $no = 0;
            foreach ($master->v_invoice_details as $detail){
                if ($detail["isLabel"] == "0") {
                    ++$no;
                    $detail["no"] = $no;
                }  else {
                    $detail["no"] = "";
                }
            }
        }
        return $masters;
    }


	public function update(Request $request) {
        $reqMaster =  $request->master;
        $reqDetails =  $request->details;

		DB::beginTransaction();
        try {

            $amount = 0;

            foreach ($reqDetails as $reqDetail){
                Invoice_detail::find($reqDetail['id'])->update(['memo' => $reqDetail['memo']]);
            }

            // Invoice_detail::where('invoice_master_id',$reqMaster['id'])->delete();
            // if (count($reqDetails)>0) {
            //     foreach ($reqDetails as $reqDetail){
            //         $task = Task::find($reqDetail['task_id']);
            //         $memo = preg_replace("/残[0-9]{1,2}％/","",$reqDetail['memo']);
            //         Invoice_detail::create([
            //             'class' => $reqDetail['class'],
            //             'invoice_master_id' => $reqDetail['v_invoice_master_id'],
            //             'task_id' => $reqDetail['task_id'],
            //             'yield_ratio' => $reqDetail['yield_ratio'],
            //             'yield_amount' => $reqDetail['yield_amount'],
            //             // 'fraction' => $reqDetail['fraction'],
            //             'memo' => $memo,
            //         ]);
            //         $amount = $amount + $reqDetail['yield_amount'];
            //     }
            // } else {
            //     $amount = $reqMaster['amount'];
            // }
            $bill_amount=$reqMaster['bill_amount']+$reqMaster['adjust_amount'];
            $tax = CommLib::tax($bill_amount,$reqMaster['tax_state']);
			$master = Invoice_master::find($reqMaster['id']);
			// $master->invoice_id = $reqMaster['invoice_id'];
			// $master->subject_id = $reqMaster['subject_id'];
			// $master->closing_date = $reqMaster['closing_date'];
			// $master->tax_state = $reqMaster['tax_state'];
			// $master->amount = $amount;
            if ($reqMaster['tax_state']!=3) {
                $master->tax = $tax;
            }
			$master->tax = $reqMaster['tax'];
			// $master->discount = $reqMaster['discount'];
			// $master->ratio = $reqMaster['ratio'];
			$master->adjust_amount = $reqMaster['adjust_amount'];
            // $master->fraction = $reqMaster['fraction'];

            $master->bill_amount = $reqMaster['amount']-$reqMaster['discount']+$reqMaster['adjust_amount'];
            if ($reqMaster['tax_state']==2) {
                $master->bill_amount += $master->tax ;
            }
            $master->memo = $reqMaster['memo'];
            $master->adjust_title = $reqMaster['adjust_title'];
			$master->save();

            // Invoice_master::
            //     where('subject_id', $master ['subject_id'])->
            //     where('invoice_id', $master ['invoice_id'])->
            //     update(['memo' => $reqMaster['memo']]);

            $master_adjust_amount_total = Invoice_master::where('invoice_id', $reqMaster['invoice_id'])->sum('adjust_amount');
            $master_bill_amount_total = Invoice_master::where('invoice_id', $reqMaster['invoice_id'])->sum('bill_amount');

            $tax_1 = Invoice_master::where('invoice_id', $reqMaster['invoice_id'])->where('tax_state', 1)->sum('tax');
            $tax_2 = Invoice_master::where('invoice_id', $reqMaster['invoice_id'])->where('tax_state', 2)->sum('tax');
            $tax_3 = Invoice_master::where('invoice_id', $reqMaster['invoice_id'])->where('tax_state', 3)->sum('tax');
            $bill_amount = $master_bill_amount_total;
            Invoice::find($reqMaster['invoice_id'])->
                update([
                    'adjust_amount' => $master_adjust_amount_total,
                    'tax_1'=>$tax_1,
                    'tax_2'=>$tax_2,
                    'tax_3'=>$tax_3,
                    'bill_amount'=>$bill_amount+$tax_3
                ]);

            DB::commit();
            $invoice = v_invoice::find($reqMaster['invoice_id']);
            return ['invoice' => $invoice,'master'=>$reqMaster,'details'=>$reqDetails];
            // return $request;
        } catch (\PDOException $e){
            DB::rollBack();
            return $e;
        }
	}

    public function marge(Request $request) {

        $invoices = $request->invoices;
        $client_id = array_column($invoices, 'client_id');
        $id  = array_column($invoices, 'id');
        array_multisort($client_id, SORT_ASC, $id, SORT_ASC, $invoices);
        $client_id = $invoices[0]['client_id'];
        $invoice_id = $invoices[0]['id'];
        $keys =[]; 
        
        foreach ($invoices as $invoice){
            if ($client_id == $invoice['client_id']) {
                array_push($keys,$invoice['id']);
            } else {  
                $this->marge_invoice_value($keys,$invoice_id);
                $client_id = $invoice['client_id'];
                $invoice_id = $invoice['id'];
                $keys = [$invoice_id];
            }
        }

        $this->marge_invoice_value($keys,$invoice_id) ;

        return $keys;

    }

    function marge_invoice_value($keys,$invoice_id) {
        $sql = "
            sum(amount) as amount_total,
            sum(discount) as discount_total,
            sum(tax_1) as tax1_total,
            sum(tax_2) as tax2_total,
            sum(tax_3) as tax3_total,
            sum(taxAmount_1) as taxAmount_1_total,
            sum(taxAmount_2) as taxAmount_2_total,
            sum(bill_amount) as bill_amount_total
        ";        
        $invoice_marge_data = Invoice::select(DB::raw($sql))->whereIn('id',$keys)->first();
        $master_marge_data = Invoice_master::select(DB::raw("GROUP_CONCAT(memo) AS memo"))->whereIn('invoice_id',$keys)->first();         
        DB::beginTransaction();
        try {
            Invoice_master::whereIn('invoice_id',$keys)->update(['invoice_id' => $invoice_id,'memo'=>$master_marge_data->memo]);  
            Pay::whereIn('invoice_id',$keys)->update(['invoice_id' => $invoice_id]);  
            Invoice::
                find($invoice_id)->
                update([
                    'amount'=>$invoice_marge_data->amount_total,
                    'discount'=>$invoice_marge_data->discount_total,
                    'tax_1'=>$invoice_marge_data->tax1_total,
                    'tax_2'=>$invoice_marge_data->tax2_total,
                    'tax_3'=>$invoice_marge_data->tax3_total,
                    'taxAmount_1'=>$invoice_marge_data->taxAmount_1_total,
                    'taxAmount_2'=>$invoice_marge_data->taxAmount_2_total,
                    'bill_amount'=>$invoice_marge_data->bill_amount_total
                ]); 
            Invoice::whereIn('id',$keys)->where('id','!=', $invoice_id)->delete();    
            // トランザクションをコミット
			DB::commit();
            return true;
        } catch (\Exception $e) { 
            DB::rollback();
            return false;
        }  
    }

    public function reOrder(Request $request) {
        foreach ($request->masters as $master) {
            Invoice_master::find($master['id'])->update(['orderNo' => $master['orderNo']]);
        }
        return $request;
    }

    // 削除
    public function destroy($id)
    {

        $master = Invoice_master::findOrFail($id);
        $invoice = Invoice::find($master->invoice_id);
        $invoice->amount = $invoice->amount - $master->amount;
        $invoice->discount = $invoice->discount - $master->discount;
        $invoice->bill_amount = $invoice->bill_amount - $master->bill_amount;
        if ($master->tax_state==1) {
            $invoice->tax_1 = $invoice->tax_1 - $master->tax;
        } elseif ($master->tax_state==2) {
            $invoice->tax_2 = $invoice->tax_2 - $master->tax;
        }

        DB::beginTransaction();
        try {
            $invoice->save();
            $master->delete();
            Pay::where('invoice_id',$master->invoice_id)->
                where('subject_id',$master->subject_id)->
                delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return $master;

    }




}
