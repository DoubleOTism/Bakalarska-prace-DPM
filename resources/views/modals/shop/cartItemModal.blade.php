<div class="modal fade" id="cartItemModal" tabindex="-1" role="dialog" aria-labelledby="cartItemModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartItemModalLabel">Editace produktu v košíku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="productNameCartItem"></p>
                <p id="productPriceCartItem"></p>
                <p id="productUnitCartItem"></p>
                <p id="productDescriptionCartItem"></p>
                <form id="cartItemForm">
                    <input type="hidden" id="cartItemId">
                    <div class="mb-3">
                        <input type="hidden" id="productQuantityUpdate" value="1">
                    </div>
                </form>
            </div>
            <div class="modal-footer fixed-footer">
                <div class="quantity-control">
                    <button type="button" class="btn btn-secondary quantity-button"
                        onclick="decrementUpdateQuantity()">&#x25C0;</button>
                    <span id="productQuantityDisplayUpdate" class="quantity-display">1</span>
                    <button type="button" class="btn btn-secondary quantity-button"
                        onclick="incrementUpdateQuantity()">&#x25B6;</button>
                </div>
                <button type="button" class="btn btn-danger" onclick="removeProduct()">Odebrat z Košíku</button>
                <button type="button" class="btn btn-primary" onclick="updateCartItem()">Uložit Změny</button> 
            </div>
        </div>
    </div>
</div>
