<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUserAccess extends Model
{
    use HasFactory;
    protected $table = 'user_type_access';
    protected $guarded = [];

    public function typeUser()
    {
        return $this->hasOne(TypeUser::class, 'id', 'type_user_id');
    }
}
