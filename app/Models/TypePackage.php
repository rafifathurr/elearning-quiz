<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePackage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function package()
    {
        return $this->hasMany(Package::class, 'id_type_package', 'id')->whereNull('deleted_at');
    }
}
