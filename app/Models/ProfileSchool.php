<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSchool extends Model
{
    use HasFactory;

    protected $table = 'profile_schools';
    protected $guarded = ['id'];
}
