<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormGuardian extends Model
{
    use HasFactory;
    protected $table = 'form_guardian';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'mother_fname',
        'mother_mname',
        'mother_lname',
        'mother_contact',
        'father_fname',
        'father_mname',
        'father_lname',
        'father_contact',
        'spouse_guardian_fname',
        'spouse_guardian_mname',
        'spouse_guardian_lname',
        'spouse_guardian_contact',
        'physician_name',
    ];

    public function personal()
    {
        return $this->belongsTo(FormPersonal::class, 'applicant_id', 'applicant_id');
    }
}
