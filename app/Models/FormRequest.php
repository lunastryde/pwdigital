<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormRequest extends Model
{
    use HasFactory;

    protected $table = 'form_requests';

    protected $primaryKey = 'request_id'; // since this is your PK
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',      // FK → form_personal
        'request_type',      // device, booklet, financial, etc.
        'status',            // pending, approved, rejected, released
        'reviewed_by',       // staff/admin user id
        'reviewed_at',
        'submitted_at',
        'remarks',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(FormPersonal::class, 'applicant_id', 'applicant_id');
    }

    // One request can belong to exactly one child type
    public function deviceRequest()
    {
        return $this->hasOne(FormRequestDevice::class, 'request_id', 'request_id');
    }

    public function bookletRequest()
    {
        return $this->hasOne(FormRequestBooklet::class, 'request_id', 'request_id');
    }

    public function financialRequest()
    {
        return $this->hasOne(FormRequestFinancial::class, 'request_id', 'request_id');
    }

    public function renewal()
    {
        return $this->hasOne(FormSupportRenewal::class, 'request_id', 'request_id');
    }

    public function loss()
    {
        return $this->hasOne(FormSupportLoss::class, 'request_id', 'request_id');
    }
}
