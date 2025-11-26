<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $table = 'support_messages';

    protected $fillable = [
        'thread_id',
        'sender_id',
        'sender_is_staff',
        'body',
        'attachment_path',
        'attachment_original_name',
        'attachment_mime',
        'attachment_size',
    ];

    protected $casts = [
        'sender_is_staff' => 'boolean',
    ];

    public function thread()
    {
        return $this->belongsTo(SupportThread::class, 'thread_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
