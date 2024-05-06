@extends('layouts.app')

@section('content')
    <div class="container overflow-y: auto; products">

        <h1>Správa Zboží</h1>
        <div class="actions d-flex justify-content-between align-items-center">
            <div class="actionButtons ml-3 justify-content-between">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    Přidat Zboží
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">
                    Nahrání fotografií
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#managePhotosModal">
                    Spravovat fotky
                </button>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="showInactiveProductsCheckbox" checked>
                <label class="form-check-label" for="showInactiveProductsCheckbox">
                    Zobrazit neaktivní položky
                </label>
            </div>
        </div>
        <table class="table table-striped mt-4 full-width-table">
            <thead>
                <tr>
                    <th>Obrázek</th>
                    <th>Název</th>
                    <th>Kód</th>
                    <th>Popis</th>
                    <th>Balení</th>
                    <th>Cena</th>
                    <th>Sleva</th>
                    <th>Sleva od</th>
                    <th>Sleva do</th>
                    <th>CPS</th>
                    <th>Akce</th>
                    <th>Stav</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="product-row" data-active="{{ $product->is_enabled ? 'true' : 'false' }}">
                        <td>
                            @foreach ($product->photos as $photo)
                                <img src="{{ asset($photo->path) }}" alt="Fotka zboží" class="img-thumbnail" width="50">
                            @endforeach
                        </td>
                        <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                        <td>{{ $product->barcode }}</td>
                        <td>
                            <span class="description-preview" data-toggle="tooltip" data-placement="top"
                                title="{{ $product->description }}">
                                {{ Str::limit($product->description, 30) }}
                            </span>

                        </td>
                        <td>{{ $product->unit }}</td>
                        <td>{{ $product->price }} Kč</td>
                        <td>{{ $product->discount ?? '-' }}</td>
                        <td>
                            @if ($product->discount_from)
                                {{ \Carbon\Carbon::parse($product->discount_from)->format('d.m.Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($product->discount_to)
                                {{ \Carbon\Carbon::parse($product->discount_to)->format('d.m.Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $product->discount ? $product->price * (1 - $product->discount / 100) : $product->price }}
                            Kč</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning btn-edit" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}" data-description="{{ $product->description }}"
                                data-unit="{{ $product->unit }}" data-price="{{ $product->price }}"
                                data-discount="{{ $product->discount ?? '' }}" data-barcode="{{ $product->barcode }}"
                                data-taxRate="{{ $product->tax_rate }}" data-priceExclTax="{{ $product->price_excl_tax }}"
                                data-bs-toggle="modal" data-bs-target="#editProductModal">Upravit</button>
                            @if ($product->discount && ($product->discount_from && $product->discount_to))
                                <button type="button" class="btn btn-sm btn-danger delete-discount"
                                    data-product-id="{{ $product->id }}">
                                    Smazat slevu
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-success btn-set-discount"
                                    data-id="{{ $product->id }}" data-bs-toggle="modal"
                                    data-bs-target="#setDiscountModal">Sleva</button>
                            @endif

                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success btn-enable" data-id="{{ $product->id }}"
                                {{ $product->is_enabled ? 'disabled' : '' }}>Povolit</button>
                            <button type="button" class="btn btn-sm btn-danger btn-disable" data-id="{{ $product->id }}"
                                {{ !$product->is_enabled ? 'disabled' : '' }}>Zakázat</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('modals.adminProducts.discountModal')
    @include('modals.adminProducts.confirmModal')
    @include('modals.adminProducts.managePhotosModal')
    @include('modals.adminProducts.photoDependencyModal')
    @include('modals.adminProducts.addProductModal')
    @include('modals.adminProducts.editProductModal')
    @include('modals.adminProducts.uploadImageModal')
    @include('modals.adminProducts.setDiscountModal')

    <script src="resources/js/fullViews/adminProducts/manageProducts.js" defer></script>
@endsection
