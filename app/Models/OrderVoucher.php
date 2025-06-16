<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderVoucher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
