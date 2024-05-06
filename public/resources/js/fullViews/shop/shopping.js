document.addEventListener('DOMContentLoaded', function () {
    var scannerActivated = false;
    var lastScannedCode = '';

    function startScanner() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#interactive'),
                constraints: {
                    facingMode: "environment"
                },
            },
            decoder: {
                readers: ["code_128_reader"]
            },
        }, function (err) {
            if (err) {
                console.error(err);
                return;
            }
            Quagga.start();
            scannerActivated = true;
        });

        Quagga.onDetected(function (data) {
            var code = data.codeResult.code;
            if (code !== lastScannedCode) {
                lastScannedCode = code;
                fetchProductInfo(code);
            }
        });
    }

    $('#barcodeScannerModal').on('shown.bs.modal', function () {
        if (!scannerActivated) {
            startScanner();
        }
    });

    $('#barcodeScannerModal').on('hidden.bs.modal', function () {
        if (scannerActivated) {
            Quagga.stop();
            scannerActivated = false;
            lastScannedCode = '';
        }
    });
});

function fetchProductInfo(barcode) {
    fetch(`/getInfoProducts/${barcode}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;
                const priceWithDiscount = product.discount > 0
                    ? (product.price - (product.price * (product.discount / 100)))
                    : product.price;
                const originalPrice = product.price;
                const unit = product.unit;

                document.getElementById('productName').textContent = product.name;
                document.getElementById('productDescription').innerHTML = product.description.replace(/\n/g, '<br>');
                document.getElementById('productPrice').innerHTML = product.discount > 0
                    ? `Cena: <del>${originalPrice} Kč</del> <strong>${priceWithDiscount} Kč</strong>`
                    : `Cena: ${originalPrice} Kč`;
                document.getElementById('productUnit').textContent = `Balení: ${unit}`;
                document.getElementById('productId').value = product.id;
                updateCarousel(product.images);

                if (typeof Quagga !== 'undefined') {
                    Quagga.stop();
                }

                $('#productInfoModal').modal('show');
                $('#barcodeScannerModal').modal('hide');
            } else {
                return;
            }
        })
        .catch(error => console.error('Error:', error));
}



function updateCarousel(images) {
    const container = document.getElementById('carouselImagesContainer');
    container.innerHTML = '';

    if (images && images.length > 0) {
        images.forEach((img, index) => {
            const itemDiv = document.createElement('div');
            itemDiv.className = 'carousel-item' + (index === 0 ? ' active' : '');
            const imgTag = document.createElement('img');
            imgTag.src = img.url;
            imgTag.className = 'd-block w-100';
            itemDiv.appendChild(imgTag);
            container.appendChild(itemDiv);
        });
    } else {
        container.innerHTML = '<p>No images available to display</p>';
    }
}

function addToCart() {
    var productId = document.getElementById('productId').value;
    var quantity = parseInt(document.getElementById('productQuantity').value,
        10);

    if (!productId || quantity <= 0) {
        alert('Prosím, zadejte platné množství.');
        return;
    }

    var sessionId = document.getElementById('sessionId').value;

    fetch('/add-to-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            sessionId: sessionId,
            productId: productId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                refreshCart()
                $('#productInfoModal').modal('hide');
            } else {
                showUniversalModal('Chyba', 'Zboží nebylo nalezeno.' + data.message, false);

            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function refreshCart() {
    var sessionId = document.getElementById('sessionId').value;

    fetch(`/refresh-cart?sessionId=${sessionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cartItemsDiv = document.getElementById('cartItems');
                cartItemsDiv.innerHTML = '';

                let newTotal = 0;
                let totalQuantity = 0;

                data.cartItems.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.className = 'cart-item';
                    itemElement.setAttribute('onclick', `openEditModal(${item.id})`);

                    const finalPrice = item.discount > 0 ? item.price * (1 - item.discount / 100) : item.price;
                    const totalItemPrice = item.quantity * finalPrice;

                    newTotal += totalItemPrice;
                    totalQuantity += item.quantity;

                    const formatter = new Intl.NumberFormat('cs-CZ', {
                        style: 'currency',
                        currency: 'CZK',
                        minimumFractionDigits: 2,
                    });

                    itemElement.innerHTML = `
                        <span>${item.name} - ${item.quantity} x ${formatter.format(finalPrice)}</span><br>
                        <span>Celkem: ${formatter.format(totalItemPrice)}</span>
                        <span id="itemQuantity${item.id}" hidden>${item.quantity}</span>
                    `;
                    cartItemsDiv.appendChild(itemElement);
                });

                const formatter = new Intl.NumberFormat('cs-CZ', {
                    style: 'currency',
                    currency: 'CZK',
                    minimumFractionDigits: 2,
                });

                const totalDisplays = document.querySelectorAll('.total-display .cart-details');
                totalDisplays.forEach(display => {
                    display.textContent = `${totalQuantity} ks - celkem ${formatter.format(newTotal)}`;
                });
            } else {
                showUniversalModal('Chyba', 'Zboží nebylo nalezeno, zkuste kód zboží lépe zaostřit.', false);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateCartItem() {
    const cartItemId = $('#cartItemId').val();
    const quantity = $('#productQuantityUpdate').val();

    fetch('/update-cart-item', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cartItemId: cartItemId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#cartItemModal').modal('hide');
                refreshCart()
            } else {
                alert('Chyba při aktualizaci produktu: ' + data.message);
            }
        });
}
