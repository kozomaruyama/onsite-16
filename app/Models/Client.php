<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'class',
        'name',
        'kana',
        'nickname',
        'delegate',
        'zip',
        'address1',
        'address2',
        'address3',
        'tel1',
        'tel2',
        'fax',
        'mail',
        'bill_closing',
        'bill_issue',
        'bill_payment',
        'bill_payment_class',
        'pay_ratio',
        'pay_amount',
        'tax_state',
        'closing_date',
        'message1_title',
        'message2_title',
        'message3_title',
        'message4_title',
        'message1',
        'message2',
        'message3',
        'message4',
        'color',
        'invoice_code',
        'print_style',
        'status',
    ];

    public function Invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function v_schedule()
    {
        return $this->hasMany(v_schedule::class);
    }

}
