<?php

namespace App\Models;

use App\Models\Quiz\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultDetail extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function result()
    {
        return $this->belongsTo(Result::class, 'result_id');
    }

    public function resultQuestion()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
    public function aspect()
    {
        return $this->belongsTo(AspectQuestion::class, 'aspect_id');
    }
}
