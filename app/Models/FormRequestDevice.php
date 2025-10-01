<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormRequestDevice extends Model
{
    use HasFactory;

    protected $table = 'form_request_device';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;


    protected $fillable = [
        'request_id',          // FK to form_requests
        'reason_for_request',
        'device_requested',
        'local_social_pension',
    ];

    protected $casts = [
        'local_social_pension' => 'boolean',
    ];

    // Child belongs to parent "form_requests"
    public function request()
    {
        return $this->belongsTo(FormRequest::class, 'request_id', 'request_id');
    }
}
