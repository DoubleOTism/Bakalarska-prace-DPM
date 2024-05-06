<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Controllers\ShoppingController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('fullViews/adminProducts/manageProducts', compact('products'));
    }

    public function allProducts()
    {
        $products = Product::where('is_enabled', true)->paginate(9);
        return view('fullViews/public/productsList', compact('products'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:products,name|max:255',
            'description' => 'required|string',
            'unit' => 'required|string|max:255',
            'price' => 'required|numeric',
            'tax_rate' => 'required|string',
            'discount' => 'nullable|numeric',
            'photo_ids' => 'nullable|array',
            'photo_ids.*' => 'exists:photos,id',
            'barcode' => 'required|string|unique:products,barcode',
            'price_excl_tax' => 'required',
        ]);

        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'unit' => $validatedData['unit'],
            'price' => $validatedData['price'],
            'tax_rate' => $validatedData['tax_rate'],
            'price_excl_tax' => $validatedData['price_excl_tax'],
            'discount' => $validatedData['discount'],
            'barcode' => $validatedData['barcode']
        ]);

        if ($request->has('photo_ids')) {
            $product->photos()->attach($request->input('photo_ids'));
        }

        if ($product) {
            return response()->json(['message' => 'Zboží bylo úspěšně přidáno.'], 200);
        } else {
            return response()->json(['message' => 'Nepodařilo se přidat zboží.'], 500);
        }
    }


    public function update(Request $request)
    {
        try {
            $product = Product::find($request->id);

            // Validační pravidla
            $request->validate([
                'id' => 'required|exists:products,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'unit' => 'required|string|max:255',
                'price' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'photo_ids' => 'nullable|array',
                'photo_ids.*' => 'exists:photos,id',
                'barcode' => 'required|string|unique:products,barcode,' . $product->id,
                'pending_discount' => 'nullable|numeric',
                'price_excl_tax' => 'required',
                'tax_rate' => 'required|string',
            ]);

            if ($request->has('photo_ids')) {
                $product->photos()->sync($request->photo_ids);
            } else {
                $product->photos()->detach();
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'unit' => $request->unit,
                'price' => $request->price,
                'discount' => $request->discount,
                'barcode' => $request->barcode,
                'price_excl_tax' => $request->price_excl_tax,
                'tax_rate' => $request->tax_rate,
            ]);

            return response()->json(['message' => 'Zboží bylo úspěšně aktualizováno.']);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Došlo k chybě při aktualizaci zboží.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }



    public function setDiscount(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_amount' => 'required|numeric',
            'discount_from' => 'required|date',
            'discount_to' => 'required|date|after:discount_from',
        ]);
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['message' => 'Produkt nebyl nalezen.'], 404);
        }

        $product->pending_discount = $request->discount_amount;
        $product->discount_from = $request->discount_from;
        $product->discount_to = $request->discount_to;
        $product->save();

        return response()->json(['message' => 'Sleva byla úspěšně nastavena.']);
    }

    public function deleteDiscount($productId)
    {
        $product = Product::findOrFail($productId);
        $product->update([
            'pending_discount' => null,
            'discount' => null,
            'discount_from' => null,
            'discount_to' => null,
        ]);

        return response()->json(['message' => 'Sleva byla úspěšně odstraněna.']);
    }


    public function enable(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_enabled' => true]);

        return back()->with('success', 'Produkt byl povolen.');
    }

    public function disable(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_enabled' => false]);

        return back()->with('success', 'Produkt byl zakázán.');
    }

    public function show(Product $product)
    {
        $product->load('photos');

        return view('fullViews/public/product', compact('product'));
    }

    public function getProductByBarcode(Request $request, $barcode)
    {
        $product = Product::with('photos')->where('barcode', $barcode)->first();

        if ($product) {
            $images = $product->photos->map(function ($photo) {
                return ['url' => $photo->path];
            });


            $priceWithDiscount = $product->discount > 0 ?
                $product->price - ($product->price * ($product->discount / 100)) :
                $product->price;
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'unit' => $product->unit,
                    'price' => number_format($product->price, 2),
                    'priceWithDiscount' => number_format($priceWithDiscount, 2),
                    'priceExclTax' => number_format($product->price_excl_tax, 2),
                    'tax_rate' => $product->tax_rate,
                    'discount' => $product->discount,
                    'images' => $images,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Produkt nenalezen.'], 404);
    }

    public function getCartItemInfo(Request $request, $cartItemId)
    {
        $cartItem = CartItem::with('product.photos')->find($cartItemId);

        if ($cartItem && $cartItem->product) {
            $product = $cartItem->product;

            $images = $product->photos->map(function ($photo) {
                return ['url' => $photo->path];
            });

            $priceWithDiscount = $product->discount > 0
                ? $product->price - ($product->price * ($product->discount / 100))
                : $product->price;

            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'unit' => $product->unit,
                    'price' => number_format($product->price, 2),
                    'priceWithDiscount' => number_format($priceWithDiscount, 2),
                    'priceExclTax' => number_format($product->price_excl_tax, 2),
                    'tax_rate' => $product->tax_rate,
                    'discount' => $product->discount,
                    'images' => $images,
                ],
                'quantity' => $cartItem->quantity
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Položka košíku nebyla nalezena.'], 404);
    }



}
