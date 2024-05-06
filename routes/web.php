<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomEmailVerificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\SettingController;


/* Routy pro všechny */
Auth::routes(['verify' => true]);
Route::get('/', [IndexController::class, 'index'])->name('home');
Route::get('/gdpr', function () {return view('fullViews/public/gdpr');})->name('gdpr');
Route::get('/op', function () {return view('fullViews/public/op');})->name('op');
Route::get('/contacts', function () {return view('fullViews/public/contacts');})->name('contacts');
Route::get('/how-it-works', function () {return view('fullViews/public/howItWorks');})->name('how-it-works');
Route::get('/login/{provider}', [LoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('/login/{provider}/callback', [LoginController::class, 'handleProviderCallback']);
Route::get('/login/register', [RegisterController::class, 'create']);
Route::get('/verify-email/{token}', [CustomEmailVerificationController::class, 'verify'])->name('custom.verify.email');
Route::get('/catalog', [ProductController::class, 'allProducts'])->name('products.index');
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.view');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

/* Routy pro přihlášené uživatele (nákup a zobrazení profilu) */
Route::middleware(['auth', 'role:Uživatel'])->group(function () {
    Route::get('/scan-page', [ScanController::class, 'showScanPage'])->name('scan.page');
    Route::get('/scan-qr', [ScanController::class, 'scanQR'])->name('scan.qr');
    Route::post('/verify-qr-code', [ScanController::class, 'verifyQrCode'])->name('verify.qr');
    Route::post('/log-activity', [ScanController::class, 'logUserActivity'])->name('log.activity');
    Route::post('/shopping', [ShoppingController::class, 'startSession'])->name('shopping.start');
    Route::get('/shopping', [ShoppingController::class, 'showShoppingPage'])->name('shopping.show');
    Route::get('/getInfoProducts/{barcode}', [ProductController::class, 'getProductByBarcode']);
    Route::post('/add-to-cart', [ShoppingController::class, 'addToCart'])->name('add.to.cart');
    Route::get('/refresh-cart', [ShoppingController::class, 'refreshCart'])->name('refresh.cart');
    Route::post('/update-cart-item', [ShoppingController::class, 'updateCartItem'])->name('cart.update');
    Route::post('/remove-cart-item', [ShoppingController::class, 'removeCartItem'])->name('cart.remove');
    Route::get('/checkout/review', [ShoppingController::class, 'reviewCheckout'])->name('checkout.review.get');
    Route::post('/checkout/review', [ShoppingController::class, 'reviewCheckout'])->name('checkout.review');
    Route::post('/checkout/cancel', [ShoppingController::class, 'cancelCheckout'])->name('checkout.cancel');
    Route::post('/completeCheckout', [ShoppingController::class, 'completeCheckout'])->name('completeCheckout');
    Route::get('/order/return', [ShoppingController::class, 'handlePaymentReturn'])->name('order.return');
    Route::post('/order/notify', [ShoppingController::class, 'handlePaymentNotification'])->name('order.notify');
    Route::get('/my-orders', [ShoppingController::class, 'viewOrders'])->name('orders.view');
    Route::get('/orders/{orderId}/invoice', [ShoppingController::class, 'downloadInvoice'])->name('order.invoice.download');
    Route::match (['put', 'patch'], '/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/getCartItemInfo/{cartItemId}', [ProductController::class, 'getCartItemInfo']);
});

/* Routy pro prodejny */
Route::middleware(['auth', 'role:Administrátor,A: Prodejen'])->group(function () {
    Route::get('/manageStores', [StoreController::class, 'index'])->name('manageStores.index');
    Route::post('/stores/create', [StoreController::class, 'create'])->name('stores.create');
    Route::get('/store/{storeId}/current-code', [StoreController::class, 'getCurrentCode'])->name('store.current-code');
    Route::post('/store/{storeId}/deactivate-code', [StoreController::class, 'deactivateCode'])->name('store.deactivate-code');
    Route::post('/store/{storeId}/new-code', [StoreController::class, 'createNewCode']);
    Route::post('/stores/{storeId}/enable', [StoreController::class, 'enable'])->name('stores.enable');
    Route::post('/stores/{storeId}/disable', [StoreController::class, 'disable'])->name('stores.disable');
    Route::get('/stores/{storeId}/products', [StoreController::class, 'showProducts'])->name('stores.showProducts');
    Route::post('/stores/{storeId}/add-products', [StoreController::class, 'addProducts'])->name('stores.addProducts');
    Route::post('/store/{storeId}/product/{productId}/toggle-tracking', [StoreController::class, 'toggleTracking'])->name('store.toggleTracking');
    Route::post('/store/{storeId}/remove-product/{productId}', [StoreController::class, 'removeProduct'])->name('store.removeProduct');
    Route::post('/store/{storeId}/product/{productId}/update-quantity', [StoreController::class, 'updateProductQuantity'])->name('store.updateProductQuantity');
    Route::post('/store/{store}/update-access-code', [StoreController::class, 'updateAccessCode']);
});

/* Routy pro produkty a jejich fotografie */
Route::middleware(['auth', 'role:Administrátor,A: Produktů'])->group(function () {
    Route::get('/manageProducts', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update', [ProductController::class, 'update'])->name('products.update')->middleware('role:Superadmin');
    Route::post('/products/setDiscount', [ProductController::class, 'setDiscount'])->name('products.setDiscount');
    Route::post('/products/{id}/enable', [ProductController::class, 'enable'])->name('products.enable');
    Route::post('/products/{id}/disable', [ProductController::class, 'disable'])->name('products.disable');
    Route::delete('/products/{product}/delete-discount', [ProductController::class, 'deleteDiscount']);
    Route::post('/uploadPhotos', [PhotoController::class, 'upload'])->name('photos.upload');
    Route::post('/assignPhotos', [PhotoController::class, 'assignToProduct'])->name('photos.assign');
    Route::get('/listPhotos', [PhotoController::class, 'list'])->name('photos.list');
    Route::post('/updatePhotos/{photoId}', [PhotoController::class, 'updateAlias'])->name('photos.update');
    Route::post('/deletePhotos', [PhotoController::class, 'deletePhotos'])->name('photos.delete');
});

/* Routy pro správu uživatelů */
Route::middleware(['auth', 'role:Administrátor,A: Uživatelů'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users/{userId}/update-status', [UserController::class, 'updateStatus'])->name('admin.updateUserStatus');
    Route::post('/users/{userId}/update-roles', [UserController::class, 'updateRoles'])->name('admin.updateUserRoles');
    Route::get('/users/{userId}/roles', [UserController::class, 'getUserRoles'])->name('admin.getUserRoles');
    Route::get('/users/filter', [UserController::class, 'filter'])->name('admin.filter');
});

/* Routy pro správu aplikace */
Route::middleware(['auth', 'role:Administrátor,A: Aplikace'])->group(function () {
    Route::get('/allOrders', [ShoppingController::class, 'allOrders'])->name('orders.index');
    Route::get('/allOrders/filter', [ShoppingController::class, 'filter'])->name('orders.filter');
    Route::get('settings/lowStock', [SettingController::class, 'showLowStockAlertSettings'])->name('settings.low_stock_alert');
    Route::post('settings/lowStockAlert/update', [SettingController::class, 'updateLowStockAlert'])->name('settings.low_stock_alert.update');
    Route::post('settings/lowStockAlert/test', [SettingController::class, 'sendTestLowStockAlert'])->name('settings.low_stock_alert.test');
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
});












