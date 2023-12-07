<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'process',
        'product_class',
        'subjects_id',
        'subject_id',
        'name',
        'breakdown',
        'user_id',
        'percentage',
        'volume',
        'unit',
        'cost',
        'amount',
        'start_date',
        'end_date',
        'isDay',
        'days',
        'remain_ratio',
        'isBreakdown',
        'isLabel',
        'orderNo',
        'memo',
        'status',
    ];

    protected $guarded = array('id');

    // public function subject() {
    //     return $this->belongsTo(Subject::class);
    // }

    // public function v_subject() {
    //     return $this->belongsTo(v_subject::class);
    // }

    // public function invoice_details()
    // {
    //     return $this->hasMany(Invoice_detail::class);
    // }

}
