<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassPackage extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function package()
    {
        return $this->belongsTo(package::class, 'package_id');
    }
}
