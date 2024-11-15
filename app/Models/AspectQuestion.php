<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AspectQuestion extends Model
{
    use HasFactory;
    protected $table = 'aspect_questions';
    protected $guarded = [];
}
