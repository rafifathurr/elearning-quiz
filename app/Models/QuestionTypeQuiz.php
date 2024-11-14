<?php

namespace App\Models;

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
        return $this->hasOne(TypeQuiz::class, 'id', 'type_user_id');
    }
}
