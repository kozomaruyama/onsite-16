<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'task_id',
        'start_date',
        'end_date',
        'percentage',
    ];

    protected $guarded = array('id');

}
