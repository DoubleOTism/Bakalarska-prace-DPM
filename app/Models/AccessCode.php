<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'store_id', 'active', 'code', 'qr_path', 'qr_content'];

}
