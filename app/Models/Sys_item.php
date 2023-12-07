<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sys_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'no',
        'employee_class',
        'product_class',
        'client_class',
        'people_class',
        'subject_class',
        'task_class',
        'task_color',
        'bill_class',
        'invoice_class',
        'schedule_class',
        'process_name',
        'unit_name',
        'exec_name',
        'user_id_header',
        'tax_rate',
        'tax_enf',
        'doc_class',
        'company_id',
        'system_name',
        'edit_item',
        'edit_table',
        'color',
        'bill_payment_class',
        'status',
    ];



}
