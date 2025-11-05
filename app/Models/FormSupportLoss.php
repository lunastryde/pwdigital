<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSupportLoss extends Model
{
    use HasFactory;

    protected $table = 'form_request_loss';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'file_affidavit',
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
