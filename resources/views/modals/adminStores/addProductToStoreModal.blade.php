<div class="modal fade" id="addProductsModal" tabindex="-1" aria-labelledby="addProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductsModalLabel">Přidat Zboží do Skladu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <form id="addProductsForm" method="POST">
                @csrf
                <input type="hidden" id="storeIdForm" name="store_id" value="{{ $store->id }}">
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vybrat</th>
                                <th>Produkt</th>
                                <th>Množství</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input product-checkbox"
                                            data-product-id="{{ $product->id }}">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <input type="number" class="form-control quantity-input"
                                            data-product-id="{{ $product->id }}" name="quantities[{{ $product->id }}]"
                                            min="1" style="display: none;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="submit" class="btn btn-primary">Přidat Zboží</button>
                </div>
            </form>
        </div>
    </div>
</div>
