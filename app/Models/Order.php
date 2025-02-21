<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function orderPackages()
    {
        return $this->hasMany(OrderPackage::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orderBy()
    {
        return $this->belongsTo(User::class, 'order_by');
    }
    public function approveBy()
    {
        return $this->belongsTo(User::class, 'approval_by');
    }
}
