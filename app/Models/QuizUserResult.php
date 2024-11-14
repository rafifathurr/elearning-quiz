<?php

namespace App\Models;

use App\Models\Quiz\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUserResult extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quizAnswerResult()
    {
        return $this->hasMany(QuizUserAnswerResult::class, 'quiz_user_result_id', 'id');
    }
}
