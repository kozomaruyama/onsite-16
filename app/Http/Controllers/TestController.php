<?php

namespace App\Http\Controllers;

use App\Exports\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Models\v_schedule;

class TestController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('test');        
    }

    public function read($ym)
    {

        $schedules=v_schedule::where('start_date','>=', $ym . '-01')->where('end_date','<=', $ym . '-31')->get();

        foreach ($schedules as $schedule) {

            $isNext = false;
            if ($schedule['bill_closing'] > $schedule['bill_issue']) {
                $isNext = true;
            }

            $isEnd = false;
            if ($schedule['bill_issue'] == 31) {
                $isEnd = true;
            }

            if ($isNext) {
                if ($isEnd) {
                    $schedule['bill_issue'] = '翌月末';
                } else {
                    $schedule['bill_issue'] = '翌' . $schedule['bill_issue'];
                }
            } else {
                if ($isEnd) {
                    $schedule['bill_issue'] = '末';
                }                
            }
        }
        return $schedules;        
    }

    /**
     * 帳票のエクスポート
     */
    public function export()
    {
        // $users = User::get();
        // $view = \view('users.export', compact($users));
        // return view('sample');      
        return \Excel::download(new Export(view('sample')), 'users.xlsx');
        // return \Excel::download(new Export($view), 'users.xlsx');
    }




}
