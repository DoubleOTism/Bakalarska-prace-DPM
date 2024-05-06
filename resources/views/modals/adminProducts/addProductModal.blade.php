<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Nové Zboží</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="productName" class="form-label">Název</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Popis</label>
                        <textarea class="form-control" id="productDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="productUnit" class="form-label">Balení</label>
                        <input type="text" class="form-control" id="productUnit" name="unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="productPrice" class="form-label">Cena za balení</label>
                        <input type="number" step="0.01" class="form-control" id="productPrice" name="price"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="taxRate" class="form-label">Sazba DPH</label>
                        <select class="form-control" id="taxRate" name="tax_rate">
                            <option value="21">21%</option>
                            <option value="15">15%</option>
                            <option value="10">10%</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="priceExclTax" class="form-label">Cena bez DPH</label>
                        <input type="number" step="0.01" class="form-control" id="priceExclTax"
                            name="price_excl_tax">
                    </div>
                    <div class="mb-3">
                        <label for="productCode" class="form-label">Kód zboží</label>
                        <input type="text" class="form-control" id="productCode" name="barcode" required>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" class="form-control" id="productDiscount" name="discount">
                    </div>
                    <div class="photo-selection-container">
                        <input type="text" id="photoSearch" placeholder="Hledat fotky" class="form-control mb-3">
                        <div style="overflow-y: auto; max-height: 300px;">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Náhled</th>
                                        <th>Alias</th>
                                        <th>Výběr</th>
                                    </tr>
                                </thead>
                                <tbody id="photoGallery">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Přidat Zboží</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
