<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'subject_id',
        'start_date',
        'start_time',
        'end_date',
        'start_timestamp',
        'closing',
        'percentage',
        'pay_ratio',
        'subcontract_id',
        'person_id',
        'status',
        'memo',
        'name',
        'alarm_interval',
        'alarm',
        'color',
    ];

    protected $guarded = array('id');

    public function v_subject() {
        return $this->belongsTo(v_subject::class);
    }


    


}
