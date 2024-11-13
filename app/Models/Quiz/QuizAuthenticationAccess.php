<?php

namespace App\Models\Quiz;

use App\Models\QuizUserResult;
use App\Models\TypeUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAuthenticationAccess extends Model
{
    use HasFactory;
    protected $table = 'quiz_authentication_access';
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function quiz()
    {
        return $this->hasMany(Quiz::class, 'id', 'quiz_id');
    }
}
