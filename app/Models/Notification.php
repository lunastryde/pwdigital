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
    ];

    protected $fillable = [
        'account_id',     // FK to accounts_master (you named it account_id)
        'title',
        'message',
        'type',           // e.g. id_finalized, request_finalized, id_expiring, etc.
        'reference_id',     // polymorphic reference id (applicant_id or request_id)
        'reference_type',  // table name: 'form_personal' or 'form_requests'
        'is_read',        // tinyint/boolean
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
