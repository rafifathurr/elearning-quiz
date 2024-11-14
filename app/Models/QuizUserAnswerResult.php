<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUserAnswerResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function quizUserAnswer()
    {
        return $this->hasOne(quizUserAnswer::class, 'id', 'quiz_user_answer_id');
    }
}
