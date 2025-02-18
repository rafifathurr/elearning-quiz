<?php

namespace App\Models\Quiz;

use App\Models\QuizAspect;
use App\Models\QuizUserResult;
use App\Models\TypeUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quiz';
    protected $guarded = [];


    public function quizAspect()
    {
        return $this->hasMany(QuizAspect::class, 'quiz_id', 'id')->whereNull('deleted_at');
    }

    public function typeQuiz()
    {
        return $this->hasOne(TypeQuiz::class, 'id', 'type_quiz_id');
    }
    public function quizTypeUserAccess()
    {
        return $this->hasMany(QuizTypeUserAccess::class, 'quiz_id', 'id');
    }
    public function quizQuestion()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id', 'id');
    }

    public function quizResult()
    {
        return $this->hasMany(QuizUserResult::class, 'quiz_id');
    }

    public function quizAuthenticationAccess()
    {
        return $this->hasMany(QuizAuthenticationAccess::class, 'quiz_id', 'id');
    }
}
