<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'account_id',
        'title',
        'content',
        'image_path',
        'priority_level',
        'is_published',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }
}
