<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class LoginController extends Controller
{



  public function index() {
    return view('login');
  }

  public function getAuth(Request $request) {
    return view('welcome');
  }

  public function postAuth_login(Request $request) {
    $name = $request->name;
    $password = $request->password;
    $user = User::where('name',$name)->first();
    if (Auth::attempt(['name' => $name,'password' => $password])) {
      // if ($user[0]->role<10) {
        // return view('home', [ 'role' => $user[0]->role ]);
        $user = User::find($user->id)->update(['status' => 1]);
        return redirect('/home');
      // } else {
      //   return redirect("/user"); 
      // }
    } else {
      return back();
    }



    // if(Gate::allows('admin-higher')){
    //     return redirect("/home"); 
    // }else{
    //   return back();
    // }
  


  }

  public function postAuth_logout(Request $request) {

    $user = Auth::user();
    User::find($user->id)->update(['status' => 0]);

    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
  }


  

}