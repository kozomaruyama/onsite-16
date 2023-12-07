<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subcontractor_id',
        'amount',
        'tax',
        'ratio',
    ];

    protected $guarded = array('id');

    public function payment() {
        return $this->belongsTo(Payment::class);
    }

}
