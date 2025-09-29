<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tanggal_lahir',
        'phone_number',
        'email_verified_at',
        'verification_expires_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'address' => 'array',
    ];

    /**
     * Relasi ke tabel CartProduct (1 User bisa punya banyak cart)
     */
    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    /**
     * Relasi ke tabel Orders (1 User bisa punya banyak order)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi ke tabel address (1 User bisa punya banyak address)
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Relasi ke tabel product_review (1 User bisa punya banyak review)
     */
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }




    /**
     * Relasi ke tabel PaymentStatus (1 User bisa punya banyak Payment Status)
     */
    // public function paymentStatuses()
    // {
    //     return $this->hasMany(PaymentStatus::class);
    // }
}
