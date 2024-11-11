<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPayment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'history_payment';

    public function paymentPackage()
    {
        return $this->hasOne(PaymentPackage::class, 'id', 'payment_package_id');
    }
}
