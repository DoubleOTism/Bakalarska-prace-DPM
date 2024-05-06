function incrementUpdateQuantity() {
    let quantityInput = document.getElementById('productQuantityUpdate');
    let quantityDisplay = document.getElementById('productQuantityDisplayUpdate');
    let currentQuantity = parseInt(quantityInput.value, 10);

    quantityInput.value = currentQuantity + 1;
    quantityDisplay.textContent = quantityInput.value;
}

function decrementUpdateQuantity() {
    let quantityInput = document.getElementById('productQuantityUpdate');
    let quantityDisplay = document.getElementById('productQuantityDisplayUpdate');
    let currentQuantity = parseInt(quantityInput.value, 10);

    if (currentQuantity > 0) {
        quantityInput.value = currentQuantity - 1;
        quantityDisplay.textContent = quantityInput.value;
    }
}

function openEditModal(cartItemId) {
    fetch(`/getCartItemInfo/${cartItemId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                const priceWithDiscount = product.discount > 0
                    ? (product.price - (product.price * (product.discount / 100)))
                    : product.price;
                const originalPrice = product.price;
                const unit = product.unit;

                document.getElementById('productNameCartItem').textContent = product.name;
                document.getElementById('productDescriptionCartItem').innerHTML = product.description.replace(/\n/g, '<br>');
                document.getElementById('productPriceCartItem').innerHTML = product.discount > 0
                    ? `Cena: <del>${originalPrice} Kč</del> <strong>${priceWithDiscount} Kč</strong>`
                    : `Cena: ${originalPrice} Kč`;
                document.getElementById('productUnitCartItem').textContent = `Balení: ${unit}`;
                document.getElementById('cartItemId').value = cartItemId;
                document.getElementById('productQuantityUpdate').value = data.quantity;
                document.getElementById('productQuantityDisplayUpdate').textContent = data.quantity;

                $('#cartItemModal').modal('show');
            } else {
                alert('Informace o produktu nebyly nalezeny.');
            }
        })
        .catch(error => console.error('Error:', error));
}

function removeProduct() {
    document.getElementById('productQuantityUpdate').value = 0;
    document.getElementById('productQuantityDisplayUpdate').textContent = 0;

    updateCartItem();
}