function incrementQuantity() {
    let quantityInput = document.getElementById('productQuantity');
    let quantityDisplay = document.getElementById('productQuantityDisplay');
    let currentQuantity = parseInt(quantityInput.value, 10);

    quantityInput.value = currentQuantity + 1;
    quantityDisplay.textContent = quantityInput.value;
}

function decrementQuantity() {
    let quantityInput = document.getElementById('productQuantity');
    let quantityDisplay = document.getElementById('productQuantityDisplay');
    let currentQuantity = parseInt(quantityInput.value, 10);

    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
        quantityDisplay.textContent = quantityInput.value;
    }
}


