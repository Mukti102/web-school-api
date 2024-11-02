<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function galery()
    {
        return $this->hasMany(FacilityGalery::class);
    }
}
