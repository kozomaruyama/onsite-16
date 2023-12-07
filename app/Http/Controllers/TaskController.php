<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Subject;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function read($id) {
        return Task::find($id);
    }


    public function calendar(Request $request)
    {
        if ($request->filter!=NULL) {
            return Task::whereBetween('start_date', [$request->start,$request->end])->orWhereBetween('end_date', [$request->start,$request->end])->whereIn('class',$request->filter)->get();
        } else {
            return Task::whereBetween('start_date', [$request->start,$request->end])->orWhereBetween('end_date', [$request->start,$request->end])->get();
        }
    }


    // 登録
    public function store(Request $request)
    {

        $subject = $request->subject;
        $tasks = $request->tasks;
        DB::beginTransaction();
        try {
            foreach ($tasks as $task){
                // $task['subject_id'] =  $subject->id;
                // $task['start_date'] = $subject->start_date;
                // $task['end_date'] = $subject->end_date;
                Task::create($task);
            }
            DB::commit();
            return null;
        } catch (\PDOException $e){
            DB::rollBack();
            return $e;
        }

    }


    public function update(Request $request)
    {

        $id = $request->id;
        $task = Task::find($id)->update($request->all());
        return $task;
    }



    // 削除
    public function destroy(Task $task)
    {
        $task->delete();
        return $task;
    }


    public function group(Request $request) {

        $selectItems = $request->selectItems;
        $values = $request->values;

        $sql ="UPDATE tasks SET ";

        foreach($values as $key => $value){
            if ($value != null) {
                $sql = $sql . $key ." = '" . $value . "',";
            }
        }
        $sql = substr($sql,0, strlen($sql) - 1). " WHERE id IN (" . $selectItems . ")";
        DB::update($sql);

        return Task::wherein('id',explode(",",$selectItems))->get();

    }



	public function subject($subjectId)
	{
		$sql = "SELECT a.*,0 As no, CONCAT(name,'(',a.id,')') AS label, b.task_class, c.unit_name
						FROM tasks a LEFT JOIN sys_items b ON a.class = b.no
						LEFT JOIN sys_items c ON a.unit = c.no
						WHERE a.subject_id = $subjectId ORDER BY a.id"
						;
		$sql = "SELECT * FROM v_tasks WHERE subject_id = $subjectId ORDER BY id";
		$records = DB::select($sql);

		$no = 0;
		$tasks = [];
		foreach ($records as $record) {
			$array = (array)$record;
			if ($array["isLabel"] == "0") {
				++$no;
				$array['no'] = $no;
			}  else {
				$array['no'] = '';
			}
			$tasks[] =  $array;
		}
		return $tasks;
	}



}

