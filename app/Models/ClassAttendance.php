<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassPackage::class, 'class_id');
    }
}
