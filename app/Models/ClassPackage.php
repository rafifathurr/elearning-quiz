<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPackage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function classCounselor()
    {
        return $this->hasMany(ClassCounselor::class, 'class_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function classAttendances()
    {
        return $this->hasMany(ClassAttendance::class, 'class_id', 'id');
    }
}
