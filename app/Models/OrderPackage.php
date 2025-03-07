<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function dateClass()
    {
        return $this->belongsTo(DateClass::class, 'date_class_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function classPackage()
    {
        return $this->hasOneThrough(
            ClassPackage::class,   // Model tujuan
            ClassUser::class, // Model perantara
            'order_package_id',   // Foreign key di ClassUser
            'id',                 // Primary key di ClassPackage
            'id',                 // Primary key di OrderPackage
            'class_id'            // Foreign key di ClassUser
        );
    }
}
