<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Person;
use App\Models\Client;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{

	public function __construct()
	{
        $this->middleware('auth');
	}


  public function index()
  {
		return User::get();
  }

  public function read($id)
  {
		return User::where('person_id',$id)->first();
  }

  public function store(Request $request)
  {

		$request->validate([
			'name' => ['required', 'string', 'max:255', 'unique:users'],
			// 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			// 'password' => ['required', 'confirmed', Rules\Password::defaults()],
			'password' => ['required'],
		]);

		$email = $request->name . "@dummy";

		$role = $this->getRole($request->person_id);

		$user = User::create([
			'name' => $request->name,
			'email' => $email,
			'password' => Hash::make($request->password),
			'person_id' => $request->person_id,
			'role' => $role,
		]);

		event(new Registered($user));

		// Auth::login($user);

		return $request;

		// return redirect(RouteServiceProvider::HOME);
  }

	// 更新
	public function update(Request $request)
	{

		$user = User::find($request->id);
		try {
			$user->name = $request->name;
			$user->password= Hash::make($request->password);
			$user->save();
			return ['status'=>0,'value'=>$user];
		} catch(\Throwable $e) {
				return ['status'=>false, 'value'=>$e];
		}
	}
	// 削除
	public function destroy(Request $request)
	{
		try {
			$user = User::find($request->id);
			$user->delete();
			return ['status'=>true,'value'=>$user];
		} catch(\Throwable $e) {
			return ['status'=>false, 'value'=>$e];
		}
	}

	public function getRole($person_id) {

		$person = Person::find($person_id);
		$client = Client::find($person->client_id);

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