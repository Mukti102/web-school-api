<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Extracuriculer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function galery(): HasMany
    {
        return $this->hasMany(ExtraCuriculerGalery::class, 'extracuriculer_id');
    }
}
