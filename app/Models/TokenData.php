<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenData extends Model
{
    use HasFactory;
    public $incrementing = false; // karena token adalah primary key bukan auto increment
    protected $primaryKey = 'token';
    protected $keyType = 'string';

    protected $fillable = [
        'token',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
