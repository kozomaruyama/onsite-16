<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\v_subject;
use App\Models\v_schedule;
use App\Models\Pay;
use App\Models\v_pay;
use App\Models\Person;

use App\Library\CommLib;

use Illuminate\Support\Facades\Auth;

class PayController extends Controller
{

	public function __construct()
	{
			$this->middleware('auth');
	}

  // 読込
	public function index() {

		$subcontracters=Client::whereBetween("class",[11,19])->get();
		$calendars=v_schedule::select('closing')
			->where('status','=',1)
			->where('percentage','>',0)
			->groupBy('closing')
			->get();


		$pay_calendars=DB::table('pays')
			->selectRaw('year(pay_date) AS year')
			->selectRaw('month(pay_date) AS month')
			->groupBy('year','month')
			->get();
			
		$user = Auth::user();
		$role = $user['role'];
		$person = Person::find($user['person_id']);
		return view('pay', compact('subcontracters','calendars','pay_calendars','role','person'));    
	}

	public function read($id) {
		return Pay::find($id);
	}


    public function extract(Request $request) {
    
        if ($request->category == "date") {
            $start = date($request->key . '-1');
            $end = date('Y-m-d',strtotime($request->key . ' last day of this month'));
            $details=v_pay::select(DB::raw("client_id,client AS name,nickname,Pay_date,closing_date"))->
                whereBetween('Pay_date',[$start, $end])->
                groupBy('client_id','client','nickname','Pay_date','closing_date')->
                get();
        } else if ($request->category == "client") {
            $details=v_pay::select(DB::raw("client_id,client AS name,nickname,Pay_date,closing_date"))->
                where('client_id',$request->key)->
                groupBy('client_id','client','nickname','Pay_date','closing_date')->  
                get();
        };
    
        return $details;
    
    }
    
	public function date($ym) {


		$start = date("Y-m-d", strtotime($ym . '-01 00:00:00'));
		$end = date("Y-m-d", strtotime($start . " 1 month"));
		$end = date("Y-m-d", strtotime($end . " -1 day"));

		$pay_calendars=DB::table('pays')
			->selectRaw('year(closing_date) AS year, month(closing_date) AS month')
			->groupBy('year','month')
			->get();
		return $pay_calendars;
		    
	}

	public function subcontracter() {
		return Client::whereBetween("class",[11,19])->get();
	}

	public function date_master($ym) {
		$start = date("Y-m-d", strtotime($ym . '-01 00:00:00'));
		$end = date("Y-m-d", strtotime($start . " 1 month"));
		$end = date("Y-m-d", strtotime($end . " -1 day"));
		$date_master=DB::table('v_pays')
			->selectRaw('client_id as id,client_id,client,nickname,closing_date,max(pay_date) AS pay_date,sum(amount) AS amount_total,"date" AS category')
			->whereBetween('closing_date',[$start,$end] )
			->groupBy('client_id','client','nickname','closing_date')
			->get();
		return $date_master;
	}

	public function date_details(Request $request) {
        $client_id = $request->client_id;
		$closing_date = $request->closing_date;
		$date_master=DB::table('v_pays')
			->where('client_id',$client_id )
			->where('closing_date',$closing_date )
			->orderBy('start_date')
			->get();
		return $date_master;
	}

	public function subcontracter_master($client_id) {
		$subcontracter_master=DB::table('v_pays')
			->selectRaw('closing_date as id, client_id,client,nickname,closing_date,max(pay_date) AS pay_date,sum(amount) AS amount_total,"subcontracter" AS category')
			->where('client_id',$client_id)
			->groupBy('closing_date','client','nickname')
			->orderBy('closing_date','desc')
			->get();
		return $subcontracter_master;
	}

	public function subcontracter_details(Request $request) {
        $client_id = $request->client_id;
		$closing_date = $request->closing_date;
		$date_master=DB::table('v_pays')
			->where('client_id',$client_id )
			->where('closing_date',$closing_date )
			->orderBy('start_date')
			->get();
		return $date_master;
	}
	
	public function close($ym) {
		// $rows = v_schedule::select('client_id as id','client','bill_issue',DB::raw('MAX(pay_ratio) AS pay_ratio'))
		$rows = v_schedule::select('client_id as id','client','nickname','bill_issue',DB::raw('sum(pay_ratio) AS pay_ratio'))
					->whereNotNull('closing')
					->where('closing', '<=',$ym)
					->where('percentage', '>',0)
					->groupBy('client_id','client','nickname','bill_issue')
					->get();
		$rows = 
		$rows2=[];
		foreach($rows as $key => $row) {
			$pay_ratio_total = v_pay_master::where('client_id',$row['id'])->sum('ratio');
			$ratio = $row['pay_ratio'];
			if ($ratio>$pay_ratio_total) {
				$row['pay_ratio']=$ratio - $pay_ratio_total;
				if ($row['pay_ratio']>0.001) {
					array_push($rows2,$row);
				}
			}
		}
		return $rows2;		
	}

    public function details(Request $request) {
		$client_id = $request->id;
		$date = $request->closing_date;

		$client = Client::find($request->id);

		$master_1 = v_pay::select(DB::raw('sum(amount) AS amount_total,min(closing_date) AS closing_date'))
			->where('client_id',$client_id)
			->where('closing_date', $date)
			->where('class','<', 40)
			->first();

		$detail_1 = v_pay::select(DB::raw('*'))
			->where('client_id',$client_id)
			->where('closing_date','=', $date)
			->where('amount','!=', 0)
			->where('class','<', 40)
			->orderBy('start_date')
			->get();

		$master_2 = v_pay::select(DB::raw('sum(amount) AS amount_total,min(closing_date) AS closing_date'))
			->where('client_id',$client_id)
			->where('closing_date', $date)
			->where('class','>=', 40)
			->first();

		$detail_2 = v_pay::select(DB::raw('*'))
			->where('client_id',$client_id)
			->where('closing_date','=', $date)
			->where('class','>=', 40)
			->get();

		return ['client'=>$client, 'master_1'=>$master_1, 'detail_1'=>$detail_1, 'master_2'=>$master_2, 'detail_2'=>$detail_2];
		
	}

	public function update(Request $request, Pay $Pay)
	{

		$errors = $this->chekeData($request);
		if (count($errors)==0) {
			if ($request->class<30) {
				$adjustPay = Pay::where('class',30)->
						where('invoice_id',$request->invoice_id)->
						where('subject_id',$request->subject_id)->
						first();
				if ($adjustPay==null) {
					$adjuctAmount = 0;
				} else {
					$adjuctAmount = $adjustPay['amount'];
				}
				Pay::where('class',30)->
						where('invoice_id',$request->invoice_id)->
						where('subject_id',$request->subject_id)->
						delete();
				$old = Pay::find($Pay['id']);
				// if ($old['amount']!=$request->amount ) {
				if ($old['amount']+$adjuctAmount-$request->amount!=0 ) {
					$new = $request->all();
					$new['class'] = 30;
					$new['cost'] = $old['amount']+$adjuctAmount-$request->amount;
					$new['amount'] = $old['amount']+$adjuctAmount-$request->amount;
					$new['volume'] = 1;
					$new['unit'] = 4;
					$new['memo'] = '調整差額分(' . $request->closing_date . '締)';
					$add = Pay::create($new);
					$addPay = v_pay::find($add['id']);
				}
			}
			$Pay->update($request->all());
			$Pay = v_pay::find($Pay['id']);
			return ['status'=>true,'value'=>$Pay];      
		} else {
			return ['status'=>false,'value'=>$errors];
		}

	}

	
    // 登録
    public function store(Request $request)
    {
        $errors = $this->chekeData($request);

        if (count($errors)>0) {

            return ['status'=>false,'value'=>$errors];

        } else {

			// $request = $this->setDefault($request);
	
			$pay = Pay::create($request->all());
	
			$pay = v_pay::find($pay['id']);
	
			return ['status'=>true,'value'=>$pay];

		}

    }

	// 削除
	public function destroy(Pay $pay)
	{
// file_put_contents("client.txt",  $client);
		try {
			$pay->delete();
			return ['status'=>true, 'value'=>$pay];
		} catch(\Throwable $e) {
			return ['status'=>false, 'value'=>$e];
		}
	}

	// 初期値セット
	function setDefault($val) {
		// if ($val->nickname == "") {
		// 	$val['nickname'] = mb_substr($val->name,0,6);
		// }       
		return $val;
	}

	// データチェック
	function chekeData($val) {
		$errors = [];
		// if ($val->class == 0) {
		// 	$errors[] = "分類が選択されていません";
		// }
		// if ($val->name == "") {
		// 	$errors[] = "名称が入力されていません";
		// }        
		return $errors;
	}

}