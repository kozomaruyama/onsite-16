<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_subject_list extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'schedule_id',
        'day',
        'start_date',
        'subject',
        'client_nick',
        'schedule_class',
        'bill_issue',
        'amount',
        'pay_ratio',
        'bill_amount',
        'pay_closing',
        'sub_nick',
        'subcontract_nick',
        'amount_subcontract',
        'pay_amount',
        'process',
    ];

}