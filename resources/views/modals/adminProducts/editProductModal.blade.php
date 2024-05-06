<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Upravit Zboží</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    @csrf
                    <input type="hidden" id="editProductId" name="id">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Název</label>
                        <input type="text" class="form-control" id="editProductName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label">Popis</label>
                        <textarea class="form-control" id="editProductDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProductUnit" class="form-label">Balení</label>
                        <input type="text" class="form-control" id="editProductUnit" name="unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Cena za balení</label>
                        <input type="number" class="form-control" id="editProductPrice" name="price" required
                            step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="editProductTaxRate" class="form-label">Sazba DPH</label>
                        <select class="form-control" id="editProductTaxRate" name="tax_rate">
                            <option value="21">21</option>
                            <option value="15">15</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPriceExcludingTax" class="form-label">Cena bez DPH</label>
                        <input type="number" class="form-control" id="editProductPriceExcludingTax"
                            name="price_excl_tax" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" id="editProductDiscount" name="discount">
                    </div>
                    <div class="mb-3">
                        <label for="editProductCode" class="form-label">Kód zboží</label>
                        <input type="text" class="form-control" id="editProductCode" name="barcode"
                            value="{{ $product->barcode }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPhotos" class="form-label">Fotografie zboží</label>
                        <div class="mb-3">
                            <input type="text" id="editPhotoSearch" class="form-control mb-3"
                                placeholder="Hledat podle aliasu">
                        </div>
                        <div id="editProductPhotoSelection" style="overflow-y: auto; max-height: 300px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Náhled</th>
                                        <th>Alias</th>
                                        <th>Výběr</th>
                                    </tr>
                                </thead>
                                <tbody id="editPhotoGallery">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Uložit Změny</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
