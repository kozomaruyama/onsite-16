<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'no',
        'link_id',
        'path',
        'name',
    ];

}
