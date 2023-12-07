<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\v_subject;
use App\Models\v_subject_list;
use App\Models\Subject;
use App\Models\Task;
use App\Models\v_task;
use App\Models\DocFile;
use App\Models\Schedule;
use App\Models\Schedule_detail;
use App\Models\Invoice_master;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;
use App\Models\Client;

class SubjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subjects=v_subject::
            // where('class','<',100)->
            where('process','<',50)->
            With('v_tasks')->
            get();
        $user = Auth::user();
        $role = $user->role;
        return view('subject', compact('subjects','role'));    

    }

    public function date($ym = 0)
    {
        if ($ym==0) {
            $subjects=v_subject::
                // where('class','<',100)->
                where('process','<',50)->
                whereBetween('start_date',[date('Y-m').'-1',date('Y-m').'-31'])->
                orderBy('start_date')->
                With('v_tasks')->
                get();
            $user = Auth::user();
            $role = $user->role;
            return view('subject', compact('subjects','role'));  
        } else {
            $subjects=v_subject::
                // where('class','<',100)->
                where('process','<',50)->
                whereBetween('start_date',[$ym.'-1',$ym.'-31'])->
                orderBy('start_date')->
                With('v_tasks')->
                get();
            return $subjects;
        }
    }
    public function prime($id)
    {
        $subjects=v_subject::
            // where('class','<',100)->
            where('process','<',50)->
            where('client_id',$id)->
            orderBy('start_date','desc')->
            With('v_tasks')->
            get();
        return $subjects;
    }
    public function subcontract($id)
    {
        $subjects=v_subject::
            // where('class','<',100)->
            where('process','<',50)->
            where('subcontract_id',$id)->
            orderBy('start_date','desc')->
            With('v_tasks')->
            get();
        return $subjects;
    }

    public function list()
    {
        $user = Auth::user();

        if ($user->role<10) {
            $subjects=Subject::
                // where('class','<',100)->
                where('process','<',40)->
                where('process','>',9)->
                with('schedules')->
                get();
        } else {
            $person = Person::find($user->person_id);
            $subjects=Subject::
                // where('class','<',100)->
                where('process','<',40)->
                where('process','>',9)->
                where('subcontract_id','=',$person->client_id)->
                with('schedules')->
                get();
        }

        return $subjects;
    }

    public function ym() {
        return Subject::select(DB::raw("YEAR(start_date) AS year,MONTH(start_date) AS month"))
            ->groupBy(DB::raw("YEAR(start_date)"))
            ->groupBy(DB::raw("MONTH(start_date)"))
            ->orderBy('start_date')
            ->get();
    }

    public function print(Request $request)
    {
        if ($request->ids!=NULL) {
            $subjects = v_subject::
                whereIn('id',$request->ids)->
                where('process','<',50)->
                with('v_tasks')->
                get();
        } else {
            $subjects = v_subject::
                where('process','<',50)->
                with('v_tasks')->
                get();
        }
        foreach ($subjects as $subject){
            $no = 0;
            foreach ($subject->v_tasks as $task){
                if ($task["isLabel"] == "0") {
                    ++$no;
                    $task["no"] = $no;
                }  else {
                    $task["no"] = "";
                }
            }
        }
        return $subjects;
    }

    public function calendar(Request $request)
    {
        if ($request->filter!=NULL) {
            return v_subject::
                // where('class','<',100)->
                where('process','>=','11')->
                where('process','<','50')->
                where('start_date','>',$request->start)->
                where('end_date','<=',$request->end)->
                whereIn('class',$request->filter)->
                with('v_tasks')->
                get();
        } else {
            return v_subject::
                // where('class','<',100)->
                where('process','>=','11')->
                where('process','<','50')->
                where('start_date','>',$request->start)->
                where('end_date','<=',$request->end)->
                with('v_tasks')->
                get();
        }
    }

    public function gantto(Request $request)
    {
        if ($request->filter!=NULL) {
            return v_subject::
                whereIn('class',$request->filter)->
                where('process','<',50)->
                with('v_tasks')->
                get();
        } else {
            return v_subject::
                with('v_tasks')->
                // where('class','<',100)->
                where('process','<',50)->
                get();
        }
    }

    public function read($id)
    {
        $v_subject = v_subject::with('v_tasks')->where('id',$id)->get();
        $v_subject = $v_subject->sortBy('v_tasks.orderNo');
        return  $v_subject[0];
    }


    public function read_in(Request $request)
    {
        return v_subject::whereIn('id',explode(",",$request->items))
                        ->where('process','<',50)
                        ->get();
    }

	public function filter(Request $request) {
        $sql = "SELECT a.*, CONCAT(a.name,'(', a.id,')') AS label,b.name AS client, c.subject_class,d.process_name FROM v_subjects a LEFT JOIN clients b ON a.client_id = b.id LEFT JOIN sys_items c ON a.class = c.no LEFT JOIN sys_items d ON a.process = d.no";
        if ($request->subjectClasses!=NULL) {
            $sql =  $sql . " WHERE a.class IN ($request->subjectClasses)";
            if ($request->processNamees!=NULL) {
                $sql =  $sql . " AND a.process IN ($request->processNamees)";
            } else if ($request->processNamees==NULL) {
                $sql =  $sql . " AND a.process < 50";
            }
        } else if ($request->processNamees!=NULL) {
            $sql =  $sql . " WHERE a.process IN ($request->processNamees)";
        } else if ($request->processNamees==NULL) {
            $sql =  $sql . " WHERE a.process < 50";
        }
        $sql =  $sql . " ORDER BY id DESC";
// file_put_contents("sql.txt",  var_export($sql, true));
        $subjects = DB::select($sql);
        return $subjects;
    }


	public function clients(Request $request)
	{

		$clients = $request->clients;
		$process = $request->process;
		$closing_date = $request->closing_date;

		$sql = "SELECT a.*,
				(CASE WHEN f.zan_ratio = 100 THEN a.pay_ratio ELSE f.zan_ratio END) bill_ratio,
				b.name AS client, c.subject_class, d.task_class AS task_state,  e.process_name AS process_name,
				f.zan_amount, f.zan_ratio
				FROM subjects a
				LEFT JOIN clients b ON a.client_id = b.id
				LEFT JOIN sys_items c ON a.class = c.no
				LEFT JOIN sys_items d ON a.task_class = d.no
				LEFT JOIN sys_items e ON a.process = e.no
				LEFT JOIN v_invoice_zan f ON a.id = f.subject_id
				WHERE a.client_id IN ($clients) AND a.process = " . $process .
                    " AND f.zan_amount > 0 " .
                    " AND a.end_date <= STR_TO_DATE(CONCAT('" . $closing_date . "-',b.bill_closing),'%Y-%m-%d')"
				;
                $subjects = DB::select($sql);

		return $subjects;
        // return v_subject::get();
	}


    // 登録
    public function store(Request $request)
    {

        $tasks = $request->tasks;
        DB::beginTransaction();
        try {
            $subject = Subject::create($request->subject);
            foreach ($tasks as $task){
                $task['subject_id'] = $subject->id;
                $task['client_id'] = $subject->client_id;
                $task['start_date'] = $subject->start_date;
                $task['end_date'] = $subject->end_date;
                Task::create($task);
            }
            $subject->save();
            DB::commit();
            // $subject=v_subject::with('v_tasks')->find($subject->id);
            $subject=v_subject_list::where('subject_id',$subject['id'])->first();
            return $subject;
        } catch (\PDOException $e){
            DB::rollBack();
            return "";
        }
    }

    // public function update(Request $request, Subject $subject) {
    public function update(Request $request) {

        $subject = $request->subject;
        if (isset($request['tasks'])) {
            $tasks = $request->tasks;
        } else {
            $tasks = [];
        }
        $mode = $request->mode;

        DB::beginTransaction();
        try {
            $newSubject = Subject::find($subject['id']);
            $newSubject->class = $subject['class'];
            $newSubject->code = $subject['code'];
            $newSubject->order_code = $subject['order_code'];
            $newSubject->name = $subject['name'];
            $newSubject->breakdown = $subject['breakdown'];
            $newSubject->client_id = $subject['client_id'];
            $newSubject->subcontract_id = $subject['subcontract_id'];
            if ($subject['subcontract_id']==101) {
                $newSubject->person_id = 0;
            } else {
                $newSubject->person_id = $subject['person_id'];
            }
            $newSubject->user_id = $subject['user_id'];
            $newSubject->percentage = $subject['percentage'];
            $newSubject->order_date = $subject['order_date'];
            $newSubject->start_date = $subject['start_date'];
            $newSubject->end_date = $subject['end_date'];
            $newSubject->isDay = $subject['isDay'];
            $newSubject->site_address = $subject['site_address'];
            $newSubject->site_name = $subject['site_name'];
            $newSubject->bill_amount = $subject['bill_amount'];
            $newSubject->bill_arriving = $subject['bill_arriving'];
            $newSubject->expenses = $subject['expenses'];
            $newSubject->tax_state = $request->subject['tax_state'];
            $newSubject->tax = $subject['tax'];
            $newSubject->discount = $subject['discount'];
            $newSubject->pay_ratio = $subject['pay_ratio'];
            $newSubject->pay_amount = $subject['pay_amount'];
            $newSubject->pay_status = $subject['pay_status'];
            $newSubject->pay_terms = $subject['pay_terms'];
            $newSubject->amount_subcontract = $subject['amount_subcontract'];
            $newSubject->process = $subject['process'];
            $newSubject->message1_title = $subject['message1_title'];
            $newSubject->message2_title = $subject['message2_title'];
            $newSubject->message3_title = $subject['message3_title'];
            $newSubject->message4_title = $subject['message4_title'];
            $newSubject->message1 = $subject['message1'];
            $newSubject->message2 = $subject['message2'];
            $newSubject->message3 = $subject['message3'];
            $newSubject->message4 = $subject['message4'];
            $newSubject->memo = $subject['memo'];
            $newSubject->status = $subject['status'];
            $newSubject->save();
            //
            if ( $mode=='with') {
                $taskIds = Task::select('id')->where('subject_id',$subject['id'])->get();
                if (count($taskIds)>0) {
                    $oldIds = array_column(json_decode($taskIds,true),'id');
                    $newIds = array_column( $tasks, 'id' );
                    $diffIds = array_diff($oldIds, $newIds);
                    // 削除
                    $delIds = array_intersect($oldIds, $diffIds);
                    Task::whereIn('id',$delIds)->delete();
                }
                $orderNo = 1;
                foreach ($tasks as $task){
                    $task['orderNo'] =$orderNo;
                    if ($task["isLabel"]==1) {
                        $task["volume"]=0;
                        $task["unit"]=0;
                        $task["cost"]=0;
                        $task["amount"]=0;
                        $task["remain_ratio"]=0;
                    };
                    if ($task['id']==-1) {
                        // 追加
                        Task::create($task);
                    } else {
                        // 更新
                        Task::find($task['id'])->update($task);
                    }
                    ++$orderNo;
                }
            }
            DB::commit();
            return v_subject_list::where('subject_id',$subject['id'])->first();
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }

    }

    public function getClosing($schedule) {
                  
        $subject = Subject::find($schedule['subject_id']);
        $client = Client::find($subject->client_id);
        if ($subject->class==1 || $subject->pay_status==10) {
            // $date = $schedule['start_date'];
            $date = $schedule['end_date'];
        } else {
            $date = $schedule['end_date'];
        }

        $closing_day = date('j', strtotime($date));
        
        if ($closing_day > $client->bill_closing) {
            return date('Y-m', strtotime($date." +1 month"));
        } else {
            return date('Y-m', strtotime($date));
        }  
    }

    // 登録
    public function storeTasks(Request $request)
    {

        $subject = $request->subject;
        $tasks = $request->tasks;

        DB::beginTransaction();
        try {
            $newSubject = Subject::find($subject['id']);
            $newSubject->breakdown = $subject['breakdown'];
            $newSubject->save();
            //
            Task::where('subject_id',$subject['id'])->delete();
            //
            // DB::table('tasks')->insert($tasks);
            foreach ($tasks as $task){
                Task::create($task);
            }

            DB::commit();
            return $subject;
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }

    }

    // 削除
    public function destroy(Subject $subject)
    {
        // ※親子関係が結ばれているレコードを削除する場合は、
        // 関係する子レコードを削除してからでないと親を削除できない。
        // $subject->delete();←はエラーになる。

        // $count = DB::table('invoice_masters')->where('subject_id',$subject->id)->count();
        // $count += DB::table('schedules')->where('subject_id',$subject->id)->count();
        // if ($count>0) {
        //     return ['status'=>false,'message'=>'別の情報から参照されているため削除できませんでした。'];
        // }

        // DB::beginTransaction();
        // try {
        //     // DB::statement('DELETE FROM tasks WHERE subject_id = ' . $subject->id );
        //     // DB::statement('DELETE FROM schedules WHERE subject_id = ' . $subject->id );
        //     // DB::statement('DELETE FROM invoice_masters WHERE subject_id = ' . $subject->id );
        //     // DB::statement('DELETE FROM subjects WHERE id = ' . $subject->id );
        //     $subject->delete();
        //     DB::commit();
        //     return ['status'=>true,'message'=>'削除されました。'];
        // } catch (\PDOException $e){
        //     DB::rollBack();
        //     return ['status'=>false,'message'=>'その他のエラーが発生し、削除に失敗しました。'];
        // }

        try {
            $subject->delete();
            return ['status'=>true, 'value'=>$subject];
        } catch(\Throwable $e) {
            return ['status'=>false, 'value'=>$e];
        }

    }

    public function group(Request $request) {

        $selectItems = $request->selectItems;
        $values = $request->values;


        $sql ="UPDATE subjects SET ";

        foreach($values as $key => $value){
            if ($value != null) {
                $sql = $sql . $key ." = '" . $value . "',";
            }
        }

        $sql = substr($sql,0, strlen($sql) - 1). " WHERE id IN (" . $selectItems . ")";

        $ret = DB::update($sql);

        return $ret;

    }



}
