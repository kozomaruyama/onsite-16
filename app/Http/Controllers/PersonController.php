<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use App\Models\Client;
use App\Models\v_person;
use App\Models\User;


class PersonController extends Controller
{

    public function __construct()
	{
        $this->middleware('auth');
	}

    public function __invoke(Request $request)
    {
        if (!\Auth::check()) {
            // return redirect('login')->with('showMessage', true);
            return redirect('login');
        }

        // ログインしていれば/sample/を表示
        // return view('sample.index');
    }


    // 読込
	public function index()
	{
        return v_person::get();
	}
    
	public function read($id)
	{
		return Person::find($id);
	}
    
	public function byClient($client_id)
	{
        return Person::
            where('client_id',$client_id)->
            orWhere('class','>=',20)->
            get();
	}    
  
    // 登録
    public function store(Request $request)
    {
        $errors = $this->chekeData($request);
        if (count($errors)>0) {
            return ['status'=>false,'value'=>$errors];
        } else {
            Person::create($request->all());
            $id = DB::getPdo()->lastInsertId();
            $person = v_person::find($id);
            return ['status'=>true,'value'=>$person];
        }
        // return Person::create($request->all());
    }

    // 更新
    public function update(Request $request, Person $person)
    {

        $errors = $this->chekeData($request);

        if (User::where('person_id',$request->id)->exists()) {
            $role = $this->getRole($request->client_id);
            User::where('person_id',$request->id)->update(['role' => $role]);
        }

        if (count($errors)>0) {
            return ['status'=>false,'value'=>$errors];
        } else {
            $person->update($request->all());
            $person = v_person::where('id',$person->id)->first();
            return ['status'=>true,'value'=>$person];
        }
        // $person->update($request->all());
        // return v_person::where('id',$person->id)->first();
    }

    // 削除
    public function destroy(Person $person)
    {
        try {
            $person->delete();
            return ['status'=>true,'value'=>$person];
        } catch(\Throwable $e) {
            return ['status'=>false, 'value'=>$e];
        }
    }

    // データチェック
    function chekeData($val) {
        $errors = [];
        if ($val->class < 20 && $val->client_id == 0) {
            $errors[] = "所属が選択されていません";
        }
        return $errors;
    }

    public function getRole($client_id) {

		$client = Client::find($client_id);

		$role = 21;
		if ($client->class>30) {
			$role = 1;
		}elseif ($client->class>25) {
			$role = 5;
		}elseif ($client->class>20) {
			$role = 7;			
		}elseif ($client->class>10) {
			$role = 11;				
		};

		return $role;

	}

}

?>