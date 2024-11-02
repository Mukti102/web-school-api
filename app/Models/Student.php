<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    use HasUlids;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
    public function angkatan()
    {
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }
    public function parent()
    {
        return $this->hasOne(StudentsParent::class);
    }
    public function adress()
    {
        return $this->hasOne(StudentsAddress::class);
    }
    public function fromSchool()
    {
        return $this->hasOne(StudentsPreviousSchool::class);
    }
}
