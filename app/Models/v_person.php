<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_person extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'class',
        'name',
        'kana',
        'birthday',
        'sex',
        'tel1',
        'tel2',
        'mail',
        'address',
        'zip',
        'status',
        'people_class'
    ];

}