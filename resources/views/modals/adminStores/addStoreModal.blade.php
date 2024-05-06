<div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStoreModalLabel">Nová Prodejna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                <form id="addStoreForm">
                    @csrf
                    <div class="mb-3">
                        <label for="storeName" class="form-label">Název Prodejny</label>
                        <input type="text" class="form-control" id="storeName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="storeDescription" class="form-label">Popis</label>
                        <textarea class="form-control" id="storeDescription" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="storeAddress" class="form-label">Adresa</label>
                        <input type="text" class="form-control" id="storeAddress" name="address" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Vytvořit Prodejnu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
