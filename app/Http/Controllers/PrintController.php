<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\Models\Person;

class PrintController extends Controller
{
  public function index()
	{
    $user = Auth::user();
    $person = Person::find($user->person_id);
    return view('print', compact('user','person'));    

  }
}