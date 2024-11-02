<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsParent extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = ['id'];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
