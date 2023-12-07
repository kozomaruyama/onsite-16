<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'post',
        'address1',
        'address2',
        'address3',
        'tel1',
        'fax1',
        'transfer_bank',
        'transfer_brach',
        'transfer_type',
        'transfer_code',
        'transfer_name',
        'message1_title',
        'message2_title',
        'message3_title',
        'message1',
        'message2',
        'message3',
        'message4',
        'invoice_code',
    ];


}