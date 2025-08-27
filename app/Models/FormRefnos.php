<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormRefnos extends Model
{
    use HasFactory;
        
    protected $table = 'form_refnos';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'refno_sss',
        'refno_gsis',
        'refno_pagibig',
        'refno_philhealth',
        'refno_others',
    ];

    public function personal()
    {
        return $this->belongsTo(FormPersonal::class, 'applicant_id', 'applicant_id');
    }
}
