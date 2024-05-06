<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $fillable = [
        'name',
        'description',
        'unit',
        'price',
        'discount',
        'pending_discount',
        'discount_from',
        'discount_to',
        'is_enabled',
        'barcode',
        'tax_rate',
        'price_excl_tax',
    ];

    public function photos()
{
    return $this->belongsToMany(Photo::class, 'product_photo');
}

public function stores()
{
    return $this->belongsToMany(Store::class, 'store_products')
                ->withPivot('quantity', 'keep_track', 'minimum_quantity_alert')
                ->withTimestamps();
}
}