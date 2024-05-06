<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ShoppingSession extends Model
{
    protected $fillable = ['user_id', 'started_at', 'ended_at', 'status', 'store_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->hasOne(ShoppingCart::class, 'session_id');
    }

    public function carts()
    {
        return $this->belongsToMany(ShoppingCart::class, 'cart_product')
            ->withPivot('quantity');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
