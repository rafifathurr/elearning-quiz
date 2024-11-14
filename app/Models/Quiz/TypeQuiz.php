<?php

namespace App\Models\Quiz;

use App\Models\QuestionTypeQuiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeQuiz extends Model
{
    use HasFactory;
    protected $table = 'type_quiz';
    protected $guarded = [];

    public function questionTypeQuiz()
    {
        return $this->hasMany(QuestionTypeQuiz::class, 'type_quiz_id', 'id');
    }
}
