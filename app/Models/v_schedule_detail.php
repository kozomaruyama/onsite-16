<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_schedule_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'v_schedule_id',
        'task_id',
        'start_date',
        'end_date',
        'percentage',
        'name',
        'remain_ratio',
        'amount',
        'pay_ratio',
        'subject_id'
    ];

    protected $guarded = array('id');

    public function v_schedule() {
        return $this->belongsTo(v_schedule::class);
    } 

}
