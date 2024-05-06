<div class="modal fade" id="setDiscountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="discountModalLabel">Nastavit Slevu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
        </div>
        <div class="modal-body">
            <form id="setDiscountForm">
                @csrf
                <input type="hidden" id="discountProductId" name="product_id">
                <div class="mb-3">
                    <label for="discountAmount" class="form-label">Výše slevy</label>
                    <input type="number" class="form-control" id="discountAmount" name="discount_amount"
                        required>
                </div>
                <div class="mb-3">
                    <label for="discountFrom" class="form-label">Sleva platná od</label>
                    <input type="datetime-local" class="form-control" id="discountFrom" name="discount_from"
                        required>
                </div>
                <div class="mb-3">
                    <label for="discountTo" class="form-label">Sleva platná do</label>
                    <input type="datetime-local" class="form-control" id="discountTo" name="discount_to"
                        required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Nastavit Slevu</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>