<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'class',
        'name',
        'breakdown',
        'kana',
        'unit',
        'cost',
        'cost_1',
        'cost_2',
        'volume',
        'items',
        'size',
        'weight',
        'isLabel',
    ];

}
