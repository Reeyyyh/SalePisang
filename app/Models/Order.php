<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'uuid',
        'invoice_number',
        'total_amount',
        'orders_status',
        'shipping_address',
        'tripay_reference',
        'payment_method',
        'payment_url',
        'paid_at',
        'payment_expired_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payment_expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function paymentStatus()
    {
        return $this->hasOne(PaymentStatus::class, 'order_id', 'id');
    }

}
