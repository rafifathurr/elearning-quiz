<?php

namespace App\Models\Quiz;

use App\Models\QuestionTypeQuiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $table = 'quiz_question';
    protected $guarded = [];


    public function quizAnswer()
    {
        return $this->hasMany(QuizAnswer::class, 'quiz_question_id', 'id')->whereNull('deleted_at');
    }

    public function questionTypeQuiz()
    {
        return $this->hasMany(QuestionTypeQuiz::class, 'question_id', 'id');
    }
}
