<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityGalery extends Model
{
    use HasFactory;
    protected $table = 'facility_galery';
    protected $guarded = ['id'];
}
