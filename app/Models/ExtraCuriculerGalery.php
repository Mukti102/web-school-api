<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCuriculerGalery extends Model
{
    use HasFactory;
    protected $table = 'extracuriculer_galery';
    protected $guarded = ['id'];
}
