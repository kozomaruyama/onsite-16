<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_invoice_master extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_class',
        'invoice_id',
        'subject_id',
        'schedule_id',
        'client_id',
        'name',
        'breakdown',
        'start_date',
        'end_date',
        'site_address',
        'site_name',
        'expenses',
        'amount',
        'tax',
        'tax_state',
        'discount_total',
        'discount',
        'ratio',
        'pay_ratio',
        'adjust_amount',
        'fraction',
        'subject_bill_amount',
        'bill_amount',
        'unit',
        'unit_name',
        'closing_date',
        'orderNo',
        'memo',
        'adjust_title',
        'code',
    ];

    protected $guarded = array('id');

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function invoice_details()
    {
        return $this->hasMany(Invoice_detail::class);
    }

    public function v_invoice_details()
    {
        return $this->hasMany(v_invoice_detail::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
