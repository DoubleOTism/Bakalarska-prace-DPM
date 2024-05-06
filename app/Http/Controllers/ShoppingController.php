<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShoppingSession;
use App\Models\ShoppingCart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Store;

use App\Models\StoreProduct;
use GoPay\Api;
use GoPay\Definition\Language;
use GoPay\Definition\TokenScope;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;



use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{
    public function downloadInvoice($orderId)
    {
        $order = Order::with('items.product')->where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        $pdf = PDF::loadView('emails/invoice', compact('order'));

        return $pdf->download('faktura-objednavka-' . $order->id . '.pdf');
    }
    public function showShoppingPage(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $store_id = $request->input('store_id');
    
            $session = ShoppingSession::where('user_id', $user_id)
                ->where('store_id', $store_id)
                ->where('status', 'active')
                ->first();
    
            if (!$session) {
                $session = new ShoppingSession([
                    'user_id' => $user_id,
                    'store_id' => $store_id,
                    'started_at' => now(),
                    'status' => 'active'
                ]);
                $session->save();
            }
    
            $cart = ShoppingCart::firstOrCreate(
                ['session_id' => $session->id],
                ['session_id' => $session->id]
            );
    
            $cart->load('items.product');
    
            $total = 0;
            foreach ($cart->items as $item) {
                $price = $item->product->discount > 0 ? $item->product->price * (1 - $item->product->discount / 100) : $item->product->price;
                $total += $item->quantity * $price;
            }
            $totalQuantity = $cart->items->sum('quantity');

    
            return view('/fullViews/shop/shopping', [
                'sessionId' => $session->id,
                'store_id' => $store_id,
                'cartItems' => $cart->items,
                'total' => $total,
                'totalQuantity' => $totalQuantity,
                'cart' => $cart
            ]);
        }
    
        return view('/fullViews/shop/shopping', [
            'cartItems' => [],
            'total' => 0
        ]);
    }
    
    public function startSession(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'You must be logged in to start shopping.');
        }

        $store_id = $request->input('store_id');
        if (!$store_id) {
            return redirect()->back()->with('error', 'Store ID is required.');
        }

        $user_id = Auth::id();
        $session = ShoppingSession::where('user_id', $user_id)
            ->where('store_id', $store_id)
            ->where('status', 'active')
            ->first();

        if (!$session) {
            $session = new ShoppingSession([
                'user_id' => $user_id,
                'store_id' => $store_id,
                'started_at' => now(),
                'status' => 'active'
            ]);
            $session->save();
        }

        $cart = ShoppingCart::firstOrCreate(
            ['session_id' => $session->id],
            ['session_id' => $session->id]
        );

        $cart->load('items.product');

        $total = 0;
        foreach ($cart->items as $item) {
            $price = $item->product->discount > 0 ? $item->product->price * (1 - $item->product->discount / 100) : $item->product->price;
            $total += $item->quantity * $price;
        }
        $totalQuantity = $cart->items->sum('quantity');

        return view('/fullViews/shop/shopping', [
            'sessionId' => $session->id,
            'store_id' => $store_id,
            'cartItems' => $cart->items,
            'total' => $total,
            'totalQuantity' => $totalQuantity,
            'cart' => $cart
        ]);
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'You must be logged in to add items to the cart.']);
        }

        $productId = $request->input('productId');
        $quantity = $request->input('quantity', 1);
        $sessionId = $request->input('sessionId');

        $cart = ShoppingCart::where('session_id', $sessionId)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Shopping cart not found.']);
        }

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }

        $finalPrice = $product->discount > 0 ? $product->price * (1 - ($product->discount / 100)) : $product->price;

        $cartItem = $cart->items()->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $finalPrice
            ]);
        }

        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cartItems' => $cart->items->load('product'),
            'total' => $cart->items->sum(function ($item) {
                return $item->quantity * $item->price;
            })
        ]);
    }
    public function refreshCart(Request $request)
    {
        $sessionId = $request->query('sessionId');
        $cart = ShoppingCart::with('items.product')->where('session_id', $sessionId)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Košík nebyl nalezen.']);
        }

        return response()->json([
            'success' => true,
            'cartItems' => $cart->items->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * $item->product->price,
                    'id' => $item->id,
                    'discount' => $item->product->discount
                ];
            })
        ]);
    }
    public function updateCartItem(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Musíte být přihlášeni k aktualizaci položky v košíku.']);
        }

        $cartItemId = $request->input('cartItemId');
        $quantity = $request->input('quantity');

        $cartItem = CartItem::find($cartItemId);

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Položka nebyla nalezena.']);
        }

        if ($quantity > 0) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            return response()->json(['success' => true, 'message' => 'Množství bylo úspěšně aktualizováno.']);
        } else if ($quantity == 0) {
            $cartItem->delete();
            return response()->json(['success' => true, 'message' => 'Položka byla úspěšně odstraněna z košíku.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Neplatné množství.']);
        }
    }
    public function reviewCheckout(Request $request)
    {
        $sessionId = $request->input('sessionId');
        $cart = ShoppingCart::with('items.product')->where('session_id', $sessionId)->first();

        if (!$cart) {
            return redirect()->route('shopping.start')->with('error', 'Váš košík je prázdný.');
        }

        return view('fullViews/shop/review', compact('cart', 'sessionId'));

    }
    public function cancelCheckout(Request $request)
    {
        $sessionId = $request->input('sessionId');
        ShoppingSession::where('id', $sessionId)->update(['status' => 'cancelled']);
        ShoppingSession::where('id', $sessionId)->update(['active' => FALSE]);
        ShoppingSession::where('id', $sessionId)->update(['ended_at' => now()]);

        return redirect('/')->with('message', 'Objednávka byla zrušena.');
    }






    public function completeCheckout(Request $request)
    {
        $sessionId = $request->input('sessionId');
        if (!$sessionId) {
            return redirect('/')->with('error', 'Chyba v nákupním procesu. Zkuste to prosím znovu.');
        }
        $session = ShoppingSession::with('store')->where('id', $sessionId)->first();

        $cart = ShoppingCart::with('items.product')->where('session_id', $sessionId)->first();
        if (!$cart || $cart->items->isEmpty()) {
            $request->session()->forget('sessionId');
            return redirect()->route('/')->with('error', 'Nelze dokončit objednávku, protože váš košík je prázdný.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $cart->items->sum(function ($item) {
                return $item->quantity * ($item->product->price - ($item->product->price * ($item->product->discount / 100)));
            }),
            'status' => 'pending'
        ]);

        foreach ($cart->items as $cartItem) {
            $discountedPrice = $cartItem->product->price * (1 - ($cartItem->product->discount / 100));
            $order->items()->create([
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
                'discounted_price' => $discountedPrice,
                'discount_rate' => $cartItem->product->discount
            ]);

        }
        $this->sendInvoiceMail($order);

        return $this->redirectToPaymentGateway($order, $sessionId);
    }

    protected function sendInvoiceMail(Order $order)
    {
        $pdf = PDF::loadView('emails/invoice', compact('order'));

        \Mail::send([], [], function ($message) use ($order, $pdf) {
            $message->to($order->user->email)
                ->subject('Faktura - objednávka #' . $order->id)
                ->attachData($pdf->output(), 'faktura-objednavka-' . $order->id . '.pdf');
        });
    }

    protected function redirectToPaymentGateway(Order $order, $sessionId)
    {
        $gopay = Api::payments([
            'goid' => env('GOPAY_GOID', ''),
            'clientId' => env('GOPAY_CLIENT_ID', ''),
            'clientSecret' => env('GOPAY_CLIENT_SECRET', ''),
            'gatewayUrl' => env('GOPAY_GATEWAY_URL', 'https://gw.sandbox.gopay.com/api'),
            'scope' => TokenScope::ALL,
            'language' => Language::CZECH,
        ]);

        $response = $gopay->createPayment([
            'amount' => $order->total * 100,
            'currency' => 'CZK',
            'order_number' => $order->id,
            'order_description' => 'Vaše objednávka č. ' . $order->id,
            'items' => $order->items->map(function ($item) {
                return [
                    'type' => 'ITEM',
                    'name' => $item->product->name,
                    'amount' => $item->product->price * $item->quantity * 100,
                    'count' => $item->quantity,
                ];
            })->toArray(),
            'callback' => [
                'return_url' => route('order.return'),
                'notification_url' => route('order.notify')
            ]
        ]);

        if ($response->hasSucceed()) {
            ShoppingSession::where('id', $sessionId)->update(['status' => 'created_request']);
            ShoppingSession::where('id', $sessionId)->update(['active' => FALSE]);
            ShoppingSession::where('id', $sessionId)->update(['ended_at' => now()]);
            return redirect($response->json['gw_url']);
        } else {
            Log::error('GoPay error: ' . $response->statusCode);
            return redirect('/')->with('error', 'Chyba při zahájení procesu platby: ' . $response->statusCode);
        }
        
    }

    public function handlePaymentReturn(Request $request)
    {
        return view('fullViews/shop/return');
    }

    public function handlePaymentNotification(Request $request)
    {

    }
    public function viewOrders()
    {
        $orders = Order::where('user_id', Auth::id())->get();

        return view('fullViews/shop/myOrders', compact('orders'));
    }

    public function allOrders()
    {
        $orders = Order::all();
        return view('fullViews/adminApp/allOrders', compact('orders'));
    }

    public function filter(Request $request)
    {
        $query = Order::query();

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->user) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->user . '%');
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();
        return view('fulLViews/adminApp/allOrders', compact('orders'));
    }




}
