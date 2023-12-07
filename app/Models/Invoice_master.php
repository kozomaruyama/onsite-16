<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_master extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'subject_id',
        'schedule_id',
        'closing_date',
        'tax_state',
        'amount',
        'tax',
        'discount',
        'ratio',
        'adjust_amount',
        'adjust_title',
        'fraction',
        'bill_amount',
        'unit',
        'orderNo',
        'memo',
        'status',
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

    // public function subject()
    // {
    //     return $this->belongsTo(Subject::class);
    // }

}
