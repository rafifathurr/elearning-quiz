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
        return $this->hasMany(classCounselor::class, 'class_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
