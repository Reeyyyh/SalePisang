<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'company',
        'address1',
        'address2',
        'country',
        'city',
        'postal',
        'phone',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $casts = [
    'is_default' => 'boolean',
];

}
