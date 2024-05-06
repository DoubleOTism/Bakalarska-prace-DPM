@extends('layouts.app')

@section('content')
    <div class="container mt-4 stores">
        <h1>Správa Prodejen</h1>
        <div class="actions d-flex justify-content-between align-items-center">
            <div class="actionButtons ml-3 justify-content-between">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStoreModal">
                    Přidat Prodejnu
                </button>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="showInactiveStoresCheckbox" checked>
                <label class="form-check-label" for="showInactiveStoresCheckbox">Zobrazit neaktivní prodejny</label>
            </div>
        </div>
        <table class="table table-striped mt-4 full-width-table">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Popis</th>
                    <th>Adresa</th>
                    <th>Akce</th>
                    <th>Stav</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stores as $store)
                    <tr class="store-row" data-active="{{ $store->is_enabled ? 'true' : 'false' }}">
                        <td>
                            <a href="{{ route('stores.showProducts', $store->id) }}">
                                <strong>{{ $store->name }}</strong></a>
                        </td>
                        <td>{{ $store->description }}</td>
                        <td>{{ $store->address }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#viewQrModal" data-store-id="{{ $store->id }}">Zobrazit/změnit QR</button>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#changeAccessCodeModal" data-store-id="{{ $store->id }}">
                                Změnit přístupový kód
                            </button>

                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success btn-enable" data-id="{{ $store->id }}"
                                {{ $store->is_enabled ? 'disabled' : '' }}>Povolit</button>
                            <button type="button" class="btn btn-sm btn-danger btn-disable" data-id="{{ $store->id }}"
                                {{ !$store->is_enabled ? 'disabled' : '' }}>Zakázat</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('modals.adminStores.viewQrModal')
    @include('modals.adminStores.changeCodeModal')
    @include('modals.adminStores.addStoreModal')
<script src="{{ asset('resources/js/fullViews/adminStores/manageStores.js') }}"></script>

@endsection
