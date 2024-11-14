<?php

namespace App\Models;

use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\TypeQuiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionTypeQuiz extends Model
{
    use HasFactory;

    protected $table = 'question_type_quiz';

    protected $guarded = [];

    public function aspect()
    {
        return $this->hasOne(TypeQuiz::class, 'id', 'type_quiz_id');
    }
    public function questionList()
    {
        return $this->hasOne(QuizQuestion::class, 'id', 'question_id');
    }
}
