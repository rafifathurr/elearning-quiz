<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TypePackage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function package()
    {
        return $this->hasMany(Package::class, 'id_type_package', 'id')
            ->whereNull('deleted_at')
            ->orderBy('price', 'DESC');
    }


    public function children()
    {
        return $this->hasMany(TypePackage::class, 'id_parent')->whereNull('deleted_at');
    }
    public function parent()
    {
        return $this->belongsTo(TypePackage::class, 'id_parent')->whereNull('deleted_at');
    }

    public function packageAccess()
    {
        return $this->hasMany(PackageAccess::class, 'type_package_id', 'id');
    }
}
