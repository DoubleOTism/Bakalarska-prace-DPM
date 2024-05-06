<div class="modal fade" id="changeAccessCodeModal" tabindex="-1" aria-labelledby="changeAccessCodeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeAccessCodeModalLabel">Změna Přístupového Kódu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changeAccessCodeForm">
                    <input type="hidden" id="storeIdForChange" value="">
                    <div class="mb-3">
                        <label for="newAccessCodeInput" class="form-label">Nový Přístupový Kód</label>
                        <input type="text" class="form-control" id="newAccessCodeInput" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Změnit Kód</button>
                </form>
            </div>
        </div>
    </div>
</div>
