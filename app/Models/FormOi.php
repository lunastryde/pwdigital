<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormOi extends Model
{
    use HasFactory;
    
    protected $table = 'form_oi';
    public $timestamps = false;

    protected $fillable = [
        'applicant_id',
        'oi_affiliated',
        'oi_contactperson',
        'oi_house_no',
        'oi_province',
        'oi_street',
        'oi_brgy',
        'oi_municipality',
        'oi_telno',
    ];

    public function personal()
    {
        return $this->belongsTo(FormPersonal::class, 'applicant_id', 'applicant_id');
    }
}
