<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormPersonal extends Model
{
    use HasFactory;
    
    protected $table = 'form_personal';
    protected $primaryKey = 'applicant_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'email',
        'applicant_type',
        'pwd_number',
        'date_applied',
        'fname',
        'mname',
        'lname',
        'suffix',
        'birthdate',
        'age',
        'sex',
        'bloodtype',
        'civil_status',
        'disability_cause',
        'disability_type',
        'house_no',
        'street',
        'barangay',
        'municipality',
        'province',
        'landline_no',
        'contact_no',
        'educ_attainment',
        'reviewed_by',
        'reviewed_on',
    ];

    // Relationships
    public function occupation()
    {
        return $this->hasOne(FormOccupation::class, 'applicant_id', 'applicant_id');
    }

    public function oi()
    {
        return $this->hasOne(FormOi::class, 'applicant_id', 'applicant_id');
    }

    public function refnos()
    {
        return $this->hasOne(FormRefnos::class, 'applicant_id', 'applicant_id');
    }

    public function guardian()
    {
        return $this->hasOne(FormGuardian::class, 'applicant_id', 'applicant_id');
    }

    public function files()
    {
        return $this->hasOne(FormFile::class, 'applicant_id', 'applicant_id');
    }
}
