<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class GanttoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $user = Auth::user();
        return view('gantto', [ 'role' => $user->role ]);        
    }

}
