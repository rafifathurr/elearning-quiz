<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeQuiz extends Model
{
    use HasFactory;
    protected $table = 'type_quiz';
    protected $guarded = [];
}
