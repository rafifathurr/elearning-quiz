<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function packageTest()
    {
        return $this->hasMany(PackageTest::class, 'package_id', 'id');
    }
    public function packageDate()
    {
        return $this->hasMany(PackageDate::class, 'package_id', 'id');
    }
    public function orderPackage()
    {
        return $this->hasMany(OrderPackage::class, 'package_id', 'id');
    }

    public function typePackage()
    {
        return $this->belongsTo(TypePackage::class, 'id_type_package');
    }

    public function voucher()
    {
        return $this->hasMany(Voucher::class, 'package_id', 'id')->whereNull('deleted_at');
    }
}
