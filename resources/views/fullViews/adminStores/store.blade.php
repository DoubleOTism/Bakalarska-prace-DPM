@extends('layouts.app')

@section('content')
    <div class="container mt-4 store">
        <h1>Produkty pro prodejnu: {{ $store->name }}</h1>

        <div class="actions d-flex justify-content-between align-items-center">
            <div class="actionButtons ml-3 justify-content-between">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductsModal">Přidat
                    Zboží</button>
            </div>
        </div>
        @if ($store->products->isEmpty())
            <p>Žádné produkty nejsou přiřazeny k tomuto obchodu.</p>
        @else
            <table class="table table-striped mt-4 full-width-table">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Množství</th>
                        <th>Sledovaný počet zboží</th>
                        <th>Změna množství</th>
                        <th>Hlídání zboží</th>
                        <th>Akce</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($store->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ $product->pivot->minimum_quantity_alert ?: '---' }}</td>
                            <td>
                                <!-- Tlačítko pro zobrazení formuláře -->
                                <button class="btn btn-sm btn-primary open-update-form"
                                    data-product-id="{{ $product->id }}">Změnit množství</button>

                                <!-- Skrytý formulář pro aktualizaci množství -->
                                <div class="update-quantity-form d-none" data-product-id="{{ $product->id }}">
                                    <form
                                        action="{{ route('store.updateProductQuantity', ['storeId' => $store->id, 'productId' => $product->id]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="number" name="newQuantity" min="0"
                                            value="{{ $product->pivot->quantity }}" required>
                                        <button type="submit" class="btn btn-sm btn-success">OK</button>
                                        <button type="button"
                                            class="btn btn-sm btn-secondary cancel-update">Zrušit</button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                @if ($product->pivot->keep_track)
                                    <button class="btn btn-warning btn-sm toggle-tracking"
                                        data-product-id="{{ $product->id }}" data-action="disable">Vypnout</button>
                                @else
                                    <button class="btn btn-info btn-sm toggle-tracking"
                                        data-product-id="{{ $product->id }}" data-action="enable">Zapnout</button>
                                @endif
                                <div class="tracking-form" data-product-id="{{ $product->id }}" style="display: none;">
                                    <form
                                        action="{{ route('store.toggleTracking', ['storeId' => $store->id, 'productId' => $product->id]) }}"
                                        method="POST" style="display: flex; gap: 10px;">
                                        @csrf
                                        <input type="number" name="minimum_quantity_alert" placeholder="Min. množství"
                                            min="1">
                                        <button type="submit" class="btn btn-primary btn-sm">OK</button>
                                        <button type="button"
                                            class="btn btn-secondary btn-sm cancel-tracking">Zrušit</button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-product"
                                    data-product-id="{{ $product->id }}">Odebrat</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <hr>
    </div>
    @include('modals.adminStores.addProductToStoreModal')


    <div id="data-holder" data-existing-product-ids="{{ json_encode($existingProductIds) }}"></div>
    <script src="{{ asset('resources/js/fullViews/adminStores/store.js') }}"></script>

@endsection
