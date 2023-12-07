<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\Schedule_detail;
use App\Models\Subject;
use App\Models\Client;
use App\Models\v_schedule;
use App\Models\v2_schedule;
use App\Models\Person;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $schedules=Schedule::get();
        return $schedules;
    }

	public function read($id)
	{
		return Schedule::find($id);
	}

	public function read_scheduleId($id)
	{
        $schedule = Schedule::find($id);
        if ($schedule['class']<30) {
            $result = v_schedule::with('v_schedule_details')->where('id',$id)->first();
        } else {
            $result = v2_schedule::where('id',$id)->first();
            $result['v_schedule_details'] = [];
        }
        return  $result;
        // return  v_schedule::with('v_schedule_details')->where('id',$id)->first();
	}

	public function read_personId($id)
	{
        Schedule::where('start_timestamp','<',time())->update(['status' => -1]);
        Schedule::where('start_timestamp','>=',time())->update(['status' => 1]);
        $schedule = Schedule::
            where('person_id',$id)->
            where('status',1)->
            where('class',30)->
            orderBy('start_timestamp')->
            get();
        return  $schedule;
        // return  v_schedule::with('v_schedule_details')->where('id',$id)->first();
	}
    
	public function read_subjectId($id)
	{
        return Schedule::where("subject_id",$id)->get();
	}

    public function managimentSheet(Request $request) {

		$result = DB::select("CALL p_getBillManageSheet($request->para1,$request->year,$request->month,$request->client)");
		return $result;

    }

	public function filter(Request $request)
	{
        $schedules=Schedule::where($request->key,"=",$request->val)->get();
        return $schedules;
	}

    public function calendar(Request $request)
    {
        $user = Auth::user();

        $start=$request->date['start'];
        $end=$request->date['end'];

        $schedules = v_schedule::with('v_schedule_details')->
            where(function($q) use  ($start,$end) { $q->
                where(function($q1) use ($start,$end) { $q1->
                    where('start_date','>=',$start)->
                    where('start_date','<=',$end);
                })->
                orWhere(function($q2)use ($start,$end) {$q2->
                    Where('end_date','>=',$start)->
                    Where('end_date','<=',$end);
                })->
                orWhere(function($q2)use ($start,$end) {$q2->
                    Where('start_date','<',$start)->
                    Where('end_date','>',$end);
                });
            });

        if ($user->role<10) {
            if ($request->client!=0) { 
                $schedules = $schedules->where('client_id',$request->client);
            }
            if ($request->subcontract!=0) { 
                $schedules = $schedules->where('subcontract_id',$request->subcontract);
            }
            if ($request->process!=[]) { 
                $schedules = $schedules->whereIn('process',$request->process);
            }
        } else {
            $schedules = $schedules->where('subcontract_id',$request->subcontract);
        }

        $result1 = $schedules->orderBy('start_date')->orderBy('id')->get();

        if ($user->role<10) {
            $result2 =  v2_schedule::
                where('person_id', $user->person_id)->
                where(function($q) use  ($start,$end) { $q->
                    where(function($q1) use ($start,$end) { $q1->
                        where('start_date','>=',$start)->
                        where('start_date','<=',$end);
                    })->
                    orWhere(function($q2)use ($start,$end) {$q2->
                        Where('end_date','>=',$start)->
                        Where('end_date','<=',$end);
                    })->
                    orWhere(function($q2)use ($start,$end) {$q2->
                        Where('start_date','<',$start)->
                        Where('end_date','>',$end);
                    });
                })->orderBy('start_date')->orderBy('id')->get();
        } else {
            $result2=  [];
        }


        return ['result1'=>$result1,'result2'=>$result2];

        // if ($user->role<10) {
        //     if ($request->client!=null) {
        //         if ($request->subcontract!=null) { 
        //             if ($request->process!=[]) {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where('start_date','>=',$request->date['start'])->
        //                     where('end_date','<=',$request->date['end'])->
        //                     where('client_id',$request->client)->
        //                     where('subcontract_id',$request->subcontract)->
        //                     whereIn('process',$request->process)->
        //                     get();
        //             } else {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where('start_date','>=',$request->date['start'])->
        //                     where('end_date','<=',$request->date['end'])->
        //                     where('client_id',$request->client)->
        //                     where('subcontract_id',$request->subcontract)->
        //                     get();
        //             }
        //         } else {
        //             if ($request->process!=[]) {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where('start_date','>=',$request->date['start'])->
        //                     where('end_date','<=',$request->date['end'])->
        //                     where('client_id',$request->client)->
        //                     whereIn('process',$request->process)->
        //                     get();
        //             } else {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where('start_date','>=',$request->date['start'])->
        //                     where('end_date','<=',$request->date['end'])->
        //                     where('client_id',$request->client)->
        //                     get();
        //             }
        //         }
        //     } else {
        //         if ($request->subcontract!=null) {
        //             if ($request->process!=[]) {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where('start_date','>=',$request->date['start'])->
        //                     where('end_date','<=',$request->date['end'])->
        //                     where('subcontract_id',$request->subcontract)->
        //                     whereIn('process',$request->process)->
        //                     get();
        //             } else {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where('start_date','>=',$request->date['start'])->
        //                     where('end_date','<=',$request->date['end'])->
        //                     where('subcontract_id',$request->subcontract)->
        //                     get();
        //             } 
        //         } else {
        //             if ($request->process!=[]) {
        //                 return v_schedule::with('v_schedule_details')->
        //                     where(function($query){$query->
        //                         where('start_date','>=',$request->date['start'])->
        //                         orWhere('end_date','<=',$request->date['end']);
        //                     })->
        //                     whereIn('process',$request->process)->
        //                     get();
        //             } else {
        //                 return $schedules->get();
        //                 return v_schedule::with('v_schedule_details')->
        //                     where(function($q) use ($start,$end) { $q->
        //                         where('start_date','>=',$start)->
        //                         where('start_date','<=',$end);
        //                     })->
        //                     orWhere(function($q2)use ($start,$end) {$q2->
        //                         Where('end_date','>=',$start)->
        //                         Where('end_date','<=',$end);
        //                     })->
        //                     orWhere(function($q2)use ($start,$end) {$q2->
        //                         Where('start_date','<',$start)->
        //                         Where('end_date','>',$end);
        //                     })->
        //                     get();
        //             }
        //         }
        //     }
        // } else {
        //     $person = Person::find($user->person_id);
        //     return v_schedule::with('v_schedule_details')->
        //         where('start_date','>=',$request->date['start'])->
        //         where('end_date','<=',$request->date['end'])->
        //         where('subcontract_id','=',$person->client_id)->
        //         get();
        // }

    }    

    public function bill(Request $request)
    {
        $user = Auth::user();

        if ($user->role==1) {
            return v_schedule::
                where('start_date','>',$request->start)->
                where('end_date','<=',$request->end)->
                where('status','>',0)->
                // ->where('status','<',9)
                // ->orderBy('start_date','asc')
                get();
        } else {
            $person = Person::find($user->person_id);
            return v_schedule::
                where('start_date','>',$request->start)->
                where('end_date','<=',$request->end)->
                where('subcontract_id','=',$person->client_id)->
                where('status','>',0)->
                // ->where('status','<',9)
                // ->orderBy('start_date','asc')
                get();
        }

    }    

    // 登録
    public function store(Request $request)
    {

        // Auth::logout();
        // if (Auth::check()==false) {
        //     return route('login');
        // }

        $errors = $this->chekeData($request);

        if (count($errors)>0) {
            return ['status'=>false,'value'=>$errors];
        } else {
            if ($request['class']<30) {
                $subject = Subject::find($request->subject_id);
                $request['subcontract_id'] = $subject->subcontract_id;
                $request['closing'] = $this->getClosing($request->subject_id,$request);
            }
            $request['start_timestamp'] = strtotime($request['start_time']);
            if ($request['start_timestamp']<time()) {
                $request['status'] = -1;
            } else {
                $request['status'] = 1;
            }
            
            DB::beginTransaction();
            try {
                Schedule::create($request->all());
                $id = DB::getPdo()->lastInsertId();
                DB::commit();
                if ($request['class']<30) {
                    $schedule = v_schedule::find($id);
                } else {
                    $schedule = v2_schedule::find($id);
                }
                return ['status'=>true,'value'=>$schedule];
            } catch (\Exception $e) {
                DB::rollback();
                return ['status'=>false,'value'=>$e];
            }

        }

        // return $subject;

    }

    // 更新
    public function update(Request $request, Schedule $schedule)
    {

        $errors = $this->chekeData($request);

        if ($request['class']<30) {
            $request['closing'] = $this->getClosing($request->subject_id,$request);
        }

        if (count($errors)>0) {
            return ['status'=>false,'value'=>$errors];
        } else {
            $request['start_timestamp'] = strtotime($request['start_time']);
            if ($request['start_timestamp']<time()) {
                $request['status'] = abs($request['status']) * (-1);
            } else {
                $request['status'] = abs($request['status']);
            }

            DB::beginTransaction();
            try {
                foreach($request->v_schedule_details as $detail) {
                    Schedule_detail::find( $detail['id'])->update(['percentage' => $detail['percentage']]);
                }
                $schedule->update($request->all());
                DB::commit();
                return ['status'=>true,'value'=>$schedule];
            } catch (\Exception $e) {
                DB::rollback();
                return ['status'=>false,'value'=>$e];
            }
        }

    }
    public function update_percentage(Request $request, Schedule $schedule)
    {

        // $request['closing'] = $this->getClosing($request->subject_id,$request);

        $schedule = Schedule::finf($request->id)
                ->update(['percentage' => $request->percentage]);
        return $schedule;
    }
    

    // 削除
    public function destroy(Schedule $schedule)
    {

        // $schedule->delete();
        // return $schedule;
        DB::beginTransaction();
        try {
            $schedule->delete();
            if ($schedule['class']<30) {
                $count = Schedule::where('subject_id',$schedule['subject_id'])->count();
                if ($count==0) {
                    // Subject::find($schedule['subject_id'])->update(['process'=>10]);
                    Subject::find($schedule['subject_id'])->delete();
                }
            }
            DB::commit();
            return ['status'=>true, 'value'=>$schedule];
        } catch(\Throwable $e) {
            DB::rollback();
            return ['status'=>false, 'value'=>$e];
        }

    }

    function getClosing($subject_id, $schedule) {

        $subject = Subject::find($subject_id);
        $client = Client::find($subject->client_id);
                         
        if ($schedule->subject_class==1 || $schedule->pay_status==10) {
            // $date = $schedule->start_date;
            $date = $schedule->end_date;
        } else {
            $date = $schedule->end_date;
        }
        
        $closing_day = date('j', strtotime($date));
        if ($closing_day > $client->bill_closing) {
            $date = date('Y-m-d',strtotime('first day of'.$date));
            return date('Y-m', strtotime($date." +1 month"));
        } else {
            return date('Y-m', strtotime($date));
        }  

    }

    // データチェック
    function chekeData($val) {
        $errors = [];
        if ($val->class<30) {
            if ($val->subject_id == 0) {
                $errors[] = "案件が選択されていません";
            }
        } else {
            if (strlen ($val->name) == 0) {
                $errors[] = "タイトルが入力されていません";
            }
        }
        return $errors;
    }

}
