<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    //relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relasi ke cart items
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}