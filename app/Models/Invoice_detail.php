<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'invoice_master_id',
        'task_id',
        'yield_ratio',
        'yield_amount',
        'remain_ratio',
        'fraction',
        'memo',
        'status',
    ];

    protected $guarded = array('id');
    
    public function invoice_master() {
        return $this->belongsTo(Invoice_master::class);
    }   
    public function v_invoice_master() {
        return $this->belongsTo(v_Invoice_master::class);
    }   

    // public function task() {
    //     return $this->belongsTo(Task::class);
    // }   

}
