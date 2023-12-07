<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_people extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'class',
        'client_id',
        'name',
        'kana',
        'birthday',
        'sex',
        'tel1',
        'tel2',
        'mail',
        'address',
        'zip',
        'color',
        'status',
        'people_class',
        'belong',
        'role',
        'account_status'
    ];

}