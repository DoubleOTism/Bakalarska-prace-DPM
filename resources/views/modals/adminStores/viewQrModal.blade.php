<div class="modal fade" id="viewQrModal" tabindex="-1" aria-labelledby="viewQrModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewQrModalLabel">QR Kód a Přístupový Kód Prodejny</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                <div id="qrCodeDisplayStore">
                </div>
                <div id="accessCodeDisplayStore">
                </div>
                <button type="button" id="deactivateCodeBtn" class="btn btn-warning" style="display: none;">Deaktivovat
                    Kód</button>
                <hr>
                <form id="newAccessCodeForm" style="display: none;">
                    <input type="hidden" id="storeIdForm">
                    <div class="mb-3">
                        <label for="newAccessCode" class="form-label">Nový Přístupový Kód Prodejny</label>
                        <input type="text" class="form-control" id="newAccessCode" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Nastavit Nový QR Kód</button>
                </form>
                <button type="button" id="showNewCodeFormBtn" class="btn btn-info">Nový Přístupový Kód</button>
            </div>
        </div>
    </div>
</div>