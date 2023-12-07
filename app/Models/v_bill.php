<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'invoice_id',
        'subject_id',
        'schedule_id',
        'client_id',
        'start_date',
        'end_date',
        'closing_date',
        'pay_date',
        'name',
        'volume',
        'unit',
        'cost',
        'amount',
        'memo',
        'client',
    ];


}