<div class="modal fade" id="productInfoModal" tabindex="-1" aria-labelledby="productInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productInfoModalLabel">Informace o Produktu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="productImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselImagesContainer"></div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <p id="productName"></p>
                <p id="productPrice"></p>
                <p id="productUnit"></p>
                <p id="productDescription"></p>

                <input type="hidden" id="productId">

                <input type="hidden" id="productQuantity" value="1">
                <input type="hidden" id="sessionId" value="{{ $sessionId }}">
            </div>
            <div class="modal-footer fixed-footer">
                <div class="quantity-control">
                    <button type="button" class="btn btn-secondary quantity-button" onclick="decrementQuantity()">&#x25C0;</button>
                    <span id="productQuantityDisplay" class="quantity-display">1</span>
                    <button type="button" class="btn btn-secondary quantity-button" onclick="incrementQuantity()">&#x25B6;</button>
                </div>
                <button type="button" class="btn btn-primary" onclick="addToCart()">Přidat do Košíku</button>
            </div>
        </div>
    </div>
</div>
