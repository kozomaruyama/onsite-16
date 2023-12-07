<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Person;

// use App\Models\v_subject;
// use App\Models\v_task;
// use App\Models\Subject;
// use App\Models\Task;
// use App\Models\DocFile;

class CalendarController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $subjects=v_subject::with('v_tasks')->get();
        $user = Auth::user();
        $person = Person::find($user['person_id']);
        $user['color'] = $person['color'];
        return view('calendar', [ 'role' => $user->role, 'user'=>$user ]);
    }

}
