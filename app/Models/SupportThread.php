<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportThread extends Model
{
    protected $table = 'support_threads';

    protected $fillable = [
        'user_id',
        'staff_id',
        'subject',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'thread_id');
    }
}
