<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'code',
        'order_code',
        'name',
        'breakdown',
        'client_id',
        'person_id',
        'subcontract_id',
        'user_id',
        'percentage',
        'order_date',
        'start_date',
        'end_date',
        'period',
        'isDay',
        'site_address',
        'site_name',
        'bill_amount',
        'bill_arriving',
        'expenses',
        'tax_state',
        'tax',
        'discount',
        'pay_ratio',
        'pay_amount',
        'pay_terms',
        'pay_status',
        'task_class',
        'process',
        'process_old',
        'file_path',
        'message1_title',
        'message2_title',
        'message3_title',
        'message4_title',
        'message1',
        'message2',
        'message3',
        'message4',
        'unVisible',
        'memo',
        'status',
        'created_at',
        'updated_at',
        'label',
        'client',
        'main_nick',
        'client_zip',
        'client_address1',
        'client_address2',
        'client_address3',
        'client_tel1',
        'client_tel2',
        'client_fax',
        'delegate',
        'bill_closing',
        'bill_issue',
        'subcontract',
        'sub_nick',
        'color',
        'subject_class',
        'task_state',
        'process_name',
        'manager_name',
        'leader',
    ];

    protected $guarded = array('id');

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function v_tasks()
    {
        return $this->hasMany(v_task::class);
    }

}
