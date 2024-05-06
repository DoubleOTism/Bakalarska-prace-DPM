<div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Nastavení slevy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="discountForm">
                        @csrf
                        <input type="hidden" id="productId" name="product_id">
                        <div class="mb-3">
                            <label for="discount" class="form-label">Sleva</label>
                            <select class="form-control" id="discount" name="discount_id">
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Uložit slevu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>