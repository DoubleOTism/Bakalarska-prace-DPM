<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Product;
use App\Models\StockHistory;


class StoreController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $stores = Store::all();
        return view('fullViews/adminStores/manageStores', compact('stores'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
        ]);

        $store = Store::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        if ($store) {
            return response()->json([
                'status' => 'success',
                'message' => 'Prodejna byla úspěšně vytvořena.',
                'store' => $store,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Prodejnu se nepodařilo vytvořit.',
            ], 500);
        }
    }


    public function createNewCode(Request $request, $storeId)
    {
        $request->validate([
            'access_code' => 'required|string',
        ]);
        $qrContent = Str::random(30);

        $qrCodeName = 'qr-codes/' . uniqid() . '.png';
        QrCode::format('png')->size(600)->generate($qrContent, storage_path('app/public/' . $qrCodeName));

        AccessCode::where('store_id', $storeId)->update(['active' => false]);
        $accessCode = AccessCode::create([
            'store_id' => $storeId,
            'user_id' => auth()->id(),
            'code' => $request->access_code,
            'qr_content' => $qrContent,
            'qr_path' => 'storage/' . $qrCodeName,
            'active' => true,
        ]);

        return response()->json(['success' => 'Nový QR kód byl úspěšně vytvořen a uložen.', 'accessCode' => $accessCode]);
    }
    public function getCurrentCode($storeId)
    {
        $code = AccessCode::where('store_id', $storeId)->where('active', true)->first();

        if (!$code) {
            return response()->json(['error' => 'Žádný aktuální kód nenalezen.'], 404);
        }
        $code->qr_path;

        return response()->json($code);
    }

    // Metoda pro deaktivaci stávajícího kódu
    public function deactivateCode($storeId)
    {
        $code = AccessCode::where('store_id', $storeId)->where('active', true)->first();

        if (!$code) {
            return response()->json(['error' => 'Žádný aktivní kód nenalezen.'], 404);
        }

        $code->active = false;
        $code->save();

        return response()->json(['success' => 'Kód byl deaktivován.']);
    }

    public function enable(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        $store->update(['is_enabled' => true]);

        return back()->with('success', 'Prodejna byla povolena.');
    }

    public function disable(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        $store->update(['is_enabled' => false]);

        return back()->with('success', 'Prodejna byla zakázána.');
    }

    public function showProducts($storeId)
    {
        $store = Store::with('products')->findOrFail($storeId);

        // Získání ID produktů již na skladě
        $existingProductIds = $store->products->pluck('id')->toArray();

        // Filtruje všechny produkty, aby zahrnuly pouze ty, které nejsou na skladě
        $products = Product::whereNotIn('id', $existingProductIds)->get();

        // Nyní předáme 'existingProductIds' i 'products' do view
        return view('fullViews/adminStores/store', compact('store', 'products', 'existingProductIds'));
    }


    public function addProducts(Request $request, $storeId)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $store = Store::findOrFail($storeId);
        $userId = auth()->id(); // Získání ID aktuálně přihlášeného uživatele

        foreach ($validated['products'] as $product) {
            $currentQuantity = 0; // Předpokládáme, že produkt ještě není ve skladu přidán
            // Zkontrolujte, zda produkt již není v obchodě přidán
            $existingProduct = $store->products()->find($product['product_id']);
            if ($existingProduct) {
                $currentQuantity = $existingProduct->pivot->quantity;
            }

            $newQuantity = $currentQuantity + $product['quantity'];

            $store->products()->syncWithoutDetaching([$product['product_id'] => ['quantity' => $newQuantity]]);

            // Záznam o změně zásob pro historii
            $store->recordStockChange(
                $product['product_id'],
                $product['quantity'],
                $userId,
                'Produkt přidán do skladu', // Poznámka k akci
                $currentQuantity // Aktuální množství před změnou
            );
        }

        return response()->json(['success' => 'Produkty byly úspěšně přidány do skladu.']);
    }

    public function toggleTracking(Request $request, $storeId, $productId)
    {
        $store = Store::findOrFail($storeId);
        $product = $store->products()->findOrFail($productId);

        // Získání hodnot z requestu
        $enableTracking = $request->input('enable_tracking', false);
        $minimumQuantityAlert = $enableTracking ? $request->input('minimum_quantity_alert', 0) : 0;

        // Aktualizace nebo vytvoření záznamu v pivot tabulce
        $store->products()->updateExistingPivot($productId, [
            'keep_track' => $enableTracking,
            'minimum_quantity_alert' => $minimumQuantityAlert,
        ]);

        return response()->json(['success' => 'Sledování produktu bylo aktualizováno.']);
    }
    public function removeProduct(Request $request, $storeId, $productId)
    {
        $store = Store::findOrFail($storeId);
        $userId = auth()->id(); // Získání ID aktuálně přihlášeného uživatele

        // Získání aktuálního množství před odebráním
        $product = $store->products()->findOrFail($productId);
        $currentQuantity = $product->pivot->quantity;

        // Odebrat produkt ze skladu
        $store->products()->detach($productId);

        // Použití metody recordStockChange pro zaznamenání odebrání produktu
        $store->recordStockChange(
            $productId,
            -$currentQuantity, // Záporná hodnota znamená odebrání
            $userId,
            'Produkt odebrán ze skladu', // Poznámka
            $currentQuantity // Předání aktuálního množství před změnou
        );

        return response()->json(['success' => 'Produkt byl úspěšně odebrán ze skladu.']);
    }
    public function updateProductQuantity(Request $request, $storeId, $productId)
    {
        $store = Store::findOrFail($storeId);
        $newQuantity = $request->input('newQuantity');
        $userId = auth()->id();

        // Získání současného množství
        $product = $store->products()->findOrFail($productId);
        $currentQuantity = $product->pivot->quantity;

        // Aktualizace množství
        $quantityChange = $newQuantity - $currentQuantity;
        $store->products()->updateExistingPivot($productId, ['quantity' => $newQuantity]);

        // Záznam změny v historii zásob
        $store->recordStockChange($productId, $quantityChange, $userId, 'Manuální aktualizace množství');

        return back()->with('success', 'Množství produktu bylo aktualizováno.');
    }

    public function verifyQrCode(Request $request)
    {
        $qrCode = $request->input('qrCode');
        $accessCode = AccessCode::where('qr_path', $qrCode)->where('active', true)->first();

        if ($accessCode) {
            // Logování skenování
            Log::info('QR Code scanned by user: ' . auth()->id(), ['qr_code' => $qrCode]);

            return response()->json([
                'success' => true,
                'message' => 'QR kód je platný.',
                'accessCode' => $accessCode->code
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Neplatný QR kód.']);
    }
    public function updateAccessCode(Request $request, $storeId)
{
    $request->validate([
        'newAccessCode' => 'required|string'
    ]);

    $accessCode = AccessCode::where('store_id', $storeId)->where('active', 1)->firstOrFail();
    $accessCode->update(['code' => $request->newAccessCode]);

    return response()->json(['success' => true, 'message' => 'Přístupový kód byl aktualizován.']);
}











}

