<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSupportRenewal extends Model
{
    use HasFactory;

    protected $table = 'form_request_renewal';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'file_medcert',
    ];

    protected $casts = [
        'file_metadata' => 'array',
        'submitted_at' => 'datetime',
    ];


    public function request()
    {
        return $this->belongsTo(FormRequest::class, 'request_id', 'request_id');
    }
}
