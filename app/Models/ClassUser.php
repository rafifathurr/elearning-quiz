<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassUser extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassPackage::class, 'class_id');
    }
    public function orderPackage()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }
}
