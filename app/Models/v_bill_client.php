<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_bill_client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'bill_amount',
        'pay_amount',
        'pay_ratio',
        'amount_zan',
        'ratio_zan',
        'bill_ratio',
    ];

}
