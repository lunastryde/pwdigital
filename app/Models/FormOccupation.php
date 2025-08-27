<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormOccupation extends Model
{
    use HasFactory;
    
    protected $table = 'form_occupation';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'occupation',
        'employment_status',
        'employment_category',
        'employment_type',
        'four_pmem',
    ];

    public function personal()
    {
        return $this->belongsTo(FormPersonal::class, 'applicant_id', 'applicant_id');
    }
}