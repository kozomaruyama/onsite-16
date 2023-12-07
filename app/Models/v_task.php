<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_task extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'class',
        'process',
        'subject_id',
        'name',
        'breakdown',
        'user_id',
        'percentage',
        'volume',
        'volume_unit',
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
        'created_at',
        'updated_at',
        'v_subject_id',
        'task_class',
        'unit_name',
        'product_class',
        'manager_name'
    ];

    protected $guarded = array('id');

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function v_subject() {
        return $this->belongsTo(v_subject::class);
    }

    public function invoice_details()
    {
        return $this->hasMany(Invoice_detail::class);
    }

}
