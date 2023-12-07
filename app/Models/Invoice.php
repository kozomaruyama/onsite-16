<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'code',
        'name',
        'closing_date',
        'issue_date',
        'payment_date',
        'amount',
        'discount',
        'adjust_amount',
        'tax_state',
        'tax_1',
        'tax_2',
        'tax_3',
        'taxAmount_1',
        'taxAmount_2',
        'bill_amount',
        'message1_title',
        'message2_title',
        'message3_title',
        'message1',
        'message2',
        'message3',
        'memo',
        'unVisible',
        'status',
        'exec_log_id',
    ];

    protected $guarded = array('id');

    // public function client()
    // {
    //     return $this->belongsTo(Client::class);
    // }

    public function v_invoice_masters()
    {
        return $this->hasMany(v_invoice_master::class);
    }

    public function invoice_masters()
    {
        return $this->hasMany(Invoice_master::class);
    }

}
