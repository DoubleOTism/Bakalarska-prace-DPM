<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path', 'uploaded_by', 'alias'];


    /**
     * Vztah k uživateli, který fotku nahrál.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Vztah k produktům, ke kterým je fotka přiřazena.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_photo');
    }
}
