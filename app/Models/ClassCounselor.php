<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassCounselor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function counselor()
    {
        return $this->hasOne(User::class, 'id', 'counselor_id');
    }
}
