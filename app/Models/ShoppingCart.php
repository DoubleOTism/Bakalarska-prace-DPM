<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = ['session_id'];

    public function session()
    {
        return $this->belongsTo(ShoppingSession::class, 'session_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id'); // Ujistěte se, že odkazuje na 'cart_id'
    }

    
}
