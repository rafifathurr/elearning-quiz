<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $table = 'quiz_question';
    protected $guarded = [];

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'id', 'quiz_id');
    }
    public function quizAnswer()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_question_id', 'id');
    }
}
