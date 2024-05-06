<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        $products = Product::all();
    
        $discountedProducts = Product::whereNotNull('discount')
                                      ->whereDate('discount_from', '<=', Carbon::now())
                                      ->whereDate('discount_to', '>=', Carbon::now())
                                      ->get();
    
        $user = auth()->user();
        $showScanButton = $user && $user->status == 'activated' && $user->hasRole('Uživatel');
    
        return view('fullViews/public/index', compact('products', 'discountedProducts', 'showScanButton'));
    }
    
}
