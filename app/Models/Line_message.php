<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line_message extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'message_type',
        'message_id',
        'message_text',
        'webhookEventId',
        'deliveryContext_isRedelivery',
        'timestamp',
        'source_type',
        'source_userId',
        'replyToken',
        'mode',
        'class',
    ];

    protected $guarded = array('id');

}
