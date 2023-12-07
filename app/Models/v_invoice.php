<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class v_invoice extends Model
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
        'amount_end',
        'discount',
        'adjust_amount',
        'tax_state',
        'tax_1',
        'tax_2',
        'tax_3',
        'bill_amount',
        'message1_title',
        'message2_title',
        'message3_title',
        'message1',
        'message2',
        'message3',
        'unVisible',
        'memo',
        'client_name',
        'invoice_code',
        'print_style',
        'yymm',
    ];

    protected $guarded = array('id');

    // public function client() {
    //     return $this->belongsTo(Client::class);
    // }

    // public function tasks()
    // {
    //     return $this->hasMany(Task::class);
    // }

    // public function v_tasks()
    // {
    //     return $this->hasMany(v_task::class);
    // }

}
