<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTabView extends Model
{
    protected $fillable = [
        'staff_id',
        'tab_key',
        'seen_count',
    ];
}
