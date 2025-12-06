<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    // match your actual table name
    protected $table = 'user_notifications';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $fillable = [
        'account_id',
        'title',
        'message',
        'type',
        'reference_id',
        'reference_type',
        'is_read',
        'expires_at',
    ];

    // fetch related model dynamically (helper)
    public function reference()
    {
        if ($this->reference_type === 'form_personal') {
            return $this->hasMany(FormPersonal::class, 'reference_id', 'applicant_id');
        } elseif ($this->reference_type === 'form_requests') {
            return $this->hasMany(FormRequest::class, 'reference_id', 'request_id');
        }
        return null;
    }
}
