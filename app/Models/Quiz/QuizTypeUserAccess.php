<?php

namespace App\Models\Quiz;

use App\Models\TypeUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTypeUserAccess extends Model
{
    use HasFactory;
    protected $table = 'quiz_type_user_access';
    protected $guarded = [];

    public function typeUser()
    {
        return $this->hasOne(TypeUser::class, 'id', 'type_user_id');
    }
}
