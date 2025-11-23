<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'accounts_profile';
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'pwd_number',
        'fname',
        'mname',
        'lname',
        'profile_picture',
        'contact_no',
        'sex',
        'age',
        'birthdate',
        'civil_status',
        'disability_type',
        'house_no',
        'street',
        'barangay',
        'municipality',
        'province',
    ];

    /**
     * Inverse relation: this profile belongs to a user in `accounts_master`.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }
}
