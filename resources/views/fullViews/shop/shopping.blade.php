@extends('layouts.app')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <div class="total-display">
        <div class="cart-info-content">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-details">
                {{ $totalQuantity ?? 0 }} ks - celkem {{ number_format($total ?? 0, 2, ',', ' ') }} Kč
            </span>
        </div>
    </div>
    <div class="shopping-container mt-2">
        <div class="container w-100">
            <h2>Košík</h2>
            <div id="cartItems">
                @forelse ($cartItems as $item)
                    @php
                        $finalPrice =
                            $item->product->discount && $item->product->discount > 0
                                ? $item->product->price * (1 - $item->product->discount / 100)
                                : $item->product->price;
                    @endphp
                    <div class="cart-item" onclick="openEditModal({{ $item->id }})">
                        <span>{{ $item->product->name }} - {{ $item->quantity }} x
                            {{ number_format($finalPrice, 2, ',', ' ') }} Kč </span><br>
                        <span>Celkem: {{ number_format($item->quantity * $finalPrice, 2, ',', ' ') }} Kč </span>
                        <span id="itemQuantity{{ $item->id }}" hidden>{{ $item->quantity }}</span>
                    </div>
                @empty
                    <div class="empty-cart-message">
                        <p>Váš košík je prázdný.</p>
                    </div>
                @endforelse
            </div>
            <div class="mb-2 mt-5 w-100">
                <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal"
                    data-bs-target="#barcodeScannerModal">
                    <i class="fas fa-barcode"></i> Skenovat
                </button>
            </div>
        </div>

    </div> 
    <div class="total-display mb-5">
        <div class="cart-info-content">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-details">
                {{ $totalQuantity ?? 0 }} ks - celkem {{ number_format($total ?? 0, 2, ',', ' ') }} Kč
            </span>
        </div>
    </div>


    <div class="mb-2 w-100">
        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#completeOrderModal">
            Dokončit nákup
        </button>
    </div>
    <div class="mb-2 w-100">
        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
            Zrušit nákup
        </button>
    </div>

    @include('modals.shop.productInfoModal')
    @include('modals.shop.barcodeReaderModal')
    @include('modals.shop.cartItemModal')
    @include('modals.shop.completeOrderModal')
    @include('modals.shop.cancelOrderModal')

    <script src="{{ asset('resources/js/fullViews/shop/shopping.js') }}"></script>
    <script src="{{ asset('resources/js/modals/shop/productInfoModal.js') }}"></script>
    <script src="{{ asset('resources/js/modals/shop/cartItemModal.js') }}"></script>



@endsection
