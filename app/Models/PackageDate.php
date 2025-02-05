<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDate extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function dateClass()
    {
        return $this->hasOne(DateClass::class, 'id', 'date_class_id');
    }
}
