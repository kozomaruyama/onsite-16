<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'class',
        'code',
        'order_code',
        'name',
        'breakdown',
        'client_id',
        'person_id',
        'subcontract_id',
        'user_id',
        'percentage',
        'order_date',
        'start_date',
        'end_date',
        'isDay',
        'site_address',
        'site_name',
        'bill_amount',
        'bill_arriving',
        'expenses',
        'tax_state',
        'tax',
        'discount',
        'pay_ratio',
        'pay_amount',
        'pay_status',
        'pay_terms',
        'amount_subcontract',
        'task_class',
        'process',
        'process_old',
        'file_path',
        'message1_title',
        'message2_title',
        'message3_title',
        'message4_title',
        'message1',
        'message2',
        'message3',
        'message4',
        'memo',
        'unVisible',
        'status',
    ];

    protected $guarded = array('id');

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function invoice_masters()
    {
        return $this->hasMany(Invoice_master::class);
    }

    public function v_invoice_masters()
    {
        return $this->hasMany(v_invoice_masters::class);
    }

    // public function set($values) {
    //     file_put_contents("subject.txt",  var_export($values, true));
    //     $this->class = 9;
    //     $this->name = "aaaa";
    // }

        # メソッド定義
  // public function getData()
  // {
  //   return $this->id . ':' . $this->name . '(' . $this->age . ')';
  // }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($subject) {
            $subject->tasks()->delete();
        });
    }

}
