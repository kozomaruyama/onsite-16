<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcontractor extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'client_id',
        'amount',
        'tax',
        'ratio',
        'chief',
        'tel',
        'email',
        'memo',
    ];

    protected $guarded = array('id');

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

}
