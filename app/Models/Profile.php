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
        'fname',
        'mname',
        'lname',
        'contact_no',
        'sex',
    ];

    /**
     * Inverse relation: this profile belongs to a user in `accounts_master`.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }
}
