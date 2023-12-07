<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_invoice_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'details_class',
        'v_invoice_master_id',
        'task_id',
        'yield_ratio',
        'yield_amount',
        'remain_ratio',
        'fraction',
        'memo',
        'class',
        'process',
        'product_class',
        'subject_id',
        'name',
        'breakdown',
        'volume',
        'unit',
        'cost',
        'amount',
        'task_status',
        'unit_name',
        'isBreakdown',
        'isLabel',
        'orderNo',
        'schedule_id',
        'invoice_id',
    ];

    protected $guarded = array('id');

    public function invoice_master() {
        return $this->belongsTo(Invoice_master::class);
    }

    public function v_invoice_master() {
        return $this->belongsTo(v_Invoice_master::class);
    }

}
