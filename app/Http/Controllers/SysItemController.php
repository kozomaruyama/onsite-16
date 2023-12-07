<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sys_item;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class SysItemController extends Controller
{
    
	public function __construct()
	{
			$this->middleware('auth');
	}

    public function index()
	{

        $user = Auth::user();

        // $sys_items = [];
        $company_id = DB::select("SELECT company_id FROM sys_items WHERE no = 1 ");
        $client_classes = DB::select("SELECT no,client_class AS name  FROM sys_items WHERE client_class IS NOT NULL AND no<30 ORDER BY no ");
        $client_classes2 = DB::select("SELECT no,client_class AS name  FROM sys_items WHERE client_class IS NOT NULL AND no<20 ORDER BY no ");
        $people_classes = DB::select("SELECT no,people_class AS name  FROM sys_items WHERE people_class IS NOT NULL AND no<30 ORDER BY no ");
        $subject_classes = DB::select("SELECT no,subject_class AS name FROM sys_items WHERE subject_class IS NOT NULL ORDER BY no ");
        $task_classes = DB::select("SELECT no,task_class AS name FROM sys_items WHERE task_class IS NOT NULL ORDER BY no ");
        $product_classes = DB::select("SELECT no,product_class AS name FROM sys_items WHERE product_class IS NOT NULL ORDER BY no ");
        $task_colors = DB::select("SELECT no,task_color AS name FROM sys_items WHERE task_color IS NOT NULL ORDER BY no ");
        $invoice_classes = DB::select("SELECT no,invoice_class AS name FROM sys_items WHERE invoice_class IS NOT NULL ORDER BY no ");
        $schedule_classes = DB::select("SELECT no,schedule_class AS name FROM sys_items WHERE schedule_class IS NOT NULL ORDER BY no ");
        $process_names = DB::select("SELECT no,process_name AS name FROM sys_items WHERE process_name IS NOT NULL ORDER BY no ");
        $colors = DB::select("SELECT no, color AS name FROM sys_items WHERE color IS NOT NULL ORDER BY no ");
        $bill_payment_class = DB::select("SELECT no, bill_payment_class AS name FROM sys_items WHERE bill_payment_class IS NOT NULL ORDER BY no ");
        $unit_names = DB::select("SELECT no,unit_name AS name FROM sys_items WHERE unit_name IS NOT NULL ORDER BY no ");
        $edit_items = DB::select("SELECT edit_table AS tableName,edit_item AS state FROM sys_items WHERE edit_table IS NOT NULL ORDER BY no ");
        $login_time = time();
        $alarms = DB::select("SELECT id,start_time,alarm_interval,status,name,memo FROM schedules WHERE class=30 AND person_id=" . $user->person_id . " ORDER BY start_timestamp");
        $sys_items = [
            'companyId'=>$company_id,
            'clientClasses'=>$client_classes,
            'clientClasses2'=>$client_classes2,
            'peopleClasses'=>$people_classes,
            'subjectClasses'=>$subject_classes,
            'taskClasses'=>$task_classes,
            'productClasses'=>$product_classes,
            'taskColors'=>$task_colors,
            'invoiceClasses'=>$invoice_classes,
            'scheduleClasses'=>$schedule_classes,
            'processNames'=>$process_names,
            'colors'=>$colors,
            'billPaymentClass'=>$bill_payment_class,
            'unitNames'=>$unit_names,
            'editItems'=>$edit_items,
            'loginTime'=>$login_time,
            'alarms'=>$alarms,
        ];
        return $sys_items;
	}
    public function taxratio($date) {
        return Sys_item::select('tax_rate')->where('id','<',11)->where('tax_enf','<=',$date)->orderBy('tax_enf','DESC')->first();
    }
}
