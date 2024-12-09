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
        return $this->belongsTo(orderPackage::class, 'order_package_id');
    }
}
