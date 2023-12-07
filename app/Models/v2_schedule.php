<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v2_schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'subject_id',
        'person_id',
        'start_date',
        'start_time',
        'alarm_time',
        'alarm_timestamp',
        'end_date',
        'closing',
        'percentage',
        'day',
        'pay_ratio',
        'status',
        'subject_class',
        'name',
        'client',
        'main_nick',
        'breakdown',
        'client_id',
        'subcontract_id',
        'site_name',
        'site_address',
        'sub_nick',
        'color',
        'expenses',
        'discount',
        'bill_closing',
        'bill_issue',
        'amount_subcontract',
        'tax_state',
        'tax_state',
        'process',
        'pay_ratio2',
        'aster_pay_ratio',
        'pay_status',
        'schedule_class',
        'memo',
        'bill_payment_class',
    ];

    protected $guarded = array('id');


}
