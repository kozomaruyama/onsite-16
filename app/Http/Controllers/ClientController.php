<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Models\v_client;
use App\Models\v_schedule;

class ClientController extends Controller
{

	public function __construct()
	{
			$this->middleware('auth');
	}

    // 読込
	public function index()
	{
        return v_client::where('class','<',20)->get();
	}
	public function prime()
	{
        return v_client::whereBetween('class',[0, 9])->get();
	}
	public function subcontracts()
	{
        return v_client::whereBetween('class',[10, 19])->orWhere('class','99')->orderBy('id')->get();
	}
	public function read($id)
	{
		return Client::find($id);
	}

	public function withSchedule($ym)
	{
        $start_date = $ym . "-01";
        $end_date = $ym . "-31";
        $res=  Client::whereHas('v_schedule' , function($query)  use ($start_date,$end_date) {
            $query->whereDate('start_date', '>=' , $start_date)->whereDate('end_date', '<=' , $end_date );
            })->get();
        return $res;
	}

	public function class($class)
	{
        $res=  Client::whereBetween('class' , [$class, $class+8])->get();
        return $res;
	}

    // 登録
    public function store(Request $request)
    {
        $errors = $this->chekeData($request);

        if (count($errors)>0) {
            return ['status'=>false,'value'=>$errors];
        }

        $request = $this->setDefault($request);

        $client = Client::create($request->all());

        $client = v_client::find($client['id']);


        return ['status'=>true,'value'=>$client];

    }

    // 更新
    public function update(Request $request, Client $client)
    {
        // $client = v_client::find($client['id']);
        // return ['status'=>true,'value'=>$client];  
         
        $errors = $this->chekeData($request);

        $id = $client['id'];

        if (count($errors)>0) {
            return ['status'=>false,'value'=>$errors];
        }
        
        $request = $this->setDefault($request);

        $client->update($request->all());

        $client = v_client::find($client['id']);

        return ['status'=>true,'value'=>$client];           

    }

    // 削除
    public function destroy(Client $client)
    {
// file_put_contents("client.txt",  $client);
        try {
            $client->delete();
            return ['status'=>true, 'value'=>$client];
        } catch(\Throwable $e) {
            return ['status'=>false, 'value'=>$e];
        }
    }

    // 一括更新
    public function group(Request $request) {

        $targetItems = $request->targetItems;
        $updateValues = $request->updateValues;

        $sql ="UPDATE clients SET ";

        $keys ="-1";
        foreach  ($targetItems as $item) {
            $keys = $keys . "," . $item["id"] ;
        }

        $fieldNames = array_keys($updateValues);
        $i = 0;
        foreach  ($updateValues as $value) {
            if ($value != "") {
                $sql = $sql . $fieldNames[$i] ." = '" . $value . "',";
            }
            $i++;
        }
        $sql = substr($sql,0, strlen($sql) - 1). " WHERE id IN (" . $keys . ")";
// file_put_contents("sql.txt",  $sql);
        DB::update($sql);

        return $sql;
    }


    // 請求状態の顧客のみ読み込む
	public function invoice()
	{
        $sql = "SELECT a.*, b.client_class
            FROM clients a LEFT JOIN sys_items b ON a.class = b.no
            WHERE a.id IN (SELECT client_id FROM subjects WHERE process>25)
            ";
        $clients = DB::select($sql);
		return $clients;
	}

    public function filter($status) {
        // file_put_contents("status.txt",  $status);
        // $sql = "SELECT a.*, b.client_class
        //         FROM clients a LEFT JOIN sys_items b ON a.class = b.no
        //         WHERE a.status LIKE '" . $status . "%'"
        //         ;
        $sql = "SELECT a.*, b.client_class
                FROM clients a LEFT JOIN sys_items b ON a.class = b.no"
                ;
        $clients = DB::select($sql);
        return $clients;
    }

    // 初期値セット
    function setDefault($val) {
        if ($val->nickname == "") {
            $val['nickname'] = mb_substr($val->name,0,6);
        }       
        return $val;
    }
    // データチェック
    function chekeData($val) {
        $errors = [];
        if ($val->class == 0) {
            $errors[] = "「分類」が選択されていません";
        }
        if ($val->name == "") {
            $errors[] = "「名称」が入力されていません";
        }        
        if ($val->bill_closing == "") {
            $errors[] = "「締切日」が入力されていません";
        }        
        // if ($val->bill_issue == "") {
        //     $errors[] = "「請求日」が入力されていません";
        // }        
        if ($val->bill_payment == "") {
            $errors[] = "「支払日」が入力されていません";
        }        
        if ($val->pay_ratio == "") {
            $errors[] = "「支払率」が入力されていません";
        }        
        return $errors;
    }

}
