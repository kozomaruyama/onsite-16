<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class MasterController extends Controller
{


  public function __construct()
  {
      $this->middleware('auth');
  }

  public function index()
  {
    $user = Auth::user();
    $role = $user->role;
// dd($user->role);
    return view('master', compact('user'));
  }

}