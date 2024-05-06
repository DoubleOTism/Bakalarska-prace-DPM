<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'address', 'is_enabled'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_products')
            ->withPivot('quantity', 'keep_track', 'minimum_quantity_alert')
            ->withTimestamps();
    }

    public function recordStockChange($productId, $quantityChange, $userId = null, $notes = null, $quantityBeforeChange = 0)
    {
        $currentQuantity = $quantityBeforeChange;
        $newQuantity = $currentQuantity + $quantityChange;
    
        // Zde již nemusíme aktualizovat množství v pivot tabulce, protože to děláme výše
    
        // Přidání záznamu do historie zásob
        StockHistory::create([
            'product_id' => $productId,
            'store_id' => $this->id,
            'user_id' => $userId,
            'quantity_change' => $quantityChange,
            'quantity_before_change' => $quantityBeforeChange, // Používáme nově předanou hodnotu
            'quantity_after_change' => $newQuantity,
            'change_type' => $quantityChange > 0 ? 'addition' : 'removal',
            'notes' => $notes
        ]);
    }

}
