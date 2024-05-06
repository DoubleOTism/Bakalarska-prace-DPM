document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.product-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const productId = this.getAttribute('data-product-id');
            const quantityInput = document.querySelector(
                '.quantity-input[data-product-id="' + productId + '"]');
            if (this.checked) {
                quantityInput.style.display =
                    'inline';
            } else {
                quantityInput.style.display = 'none';
                quantityInput.value = '';
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('addProductsForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const storeId = document.getElementById('storeIdForm').value;
        const products = [];

        document.querySelectorAll('.product-checkbox:checked').forEach(function (checkbox) {
            const productId = checkbox.getAttribute('data-product-id');
            const quantity = document.querySelector('.quantity-input[data-product-id="' +
                productId + '"]').value;

            products.push({
                product_id: productId,
                quantity: quantity
            });
        });

        fetch(`/stores/${storeId}/add-products`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
            },
            body: JSON.stringify({
                products: products
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showUniversalModal('Úspěch', 'Vybrané zboží bylo přidáno', true)
                    this.reset();
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const dataHolder = document.getElementById('data-holder');
    const existingProductIds = JSON.parse(dataHolder.dataset.existingProductIds);

    document.querySelectorAll('.product-checkbox').forEach(function (checkbox) {
        const productId = parseInt(checkbox.getAttribute('data-product-id'));
        if (existingProductIds.includes(productId)) {
            checkbox.closest('tr').style.display = 'none';
        }
    });
});





document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-tracking').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const action = this.getAttribute('data-action');
            const storeId = document.getElementById('storeIdForm').value;
            const trackingForm = document.querySelector(
                `.tracking-form[data-product-id="${productId}"]`);

            if (action === "enable") {
                trackingForm.style.display = 'flex';
                this.style.display = 'none';
            } else {
                toggleTracking(storeId, productId, false, button);
            }
        });
    });

    document.querySelectorAll('.cancel-tracking').forEach(button => {
        button.addEventListener('click', function () {
            const formDiv = this.closest('.tracking-form');
            formDiv.style.display = 'none';
            const productId = formDiv.getAttribute('data-product-id');
            document.querySelector(`.toggle-tracking[data-product-id="${productId}"]`).style
                .display = 'inline-block';
            formDiv.querySelector('form').reset();
        });
    });

    document.querySelectorAll('.tracking-form form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const productId = this.closest('.tracking-form').getAttribute(
                'data-product-id');
            const minimumQuantityAlert = this.querySelector(
                'input[name="minimum_quantity_alert"]').value;
            const storeId = document.getElementById('storeIdForm').value;
            toggleTracking(storeId, productId, true, null, minimumQuantityAlert);
            this.parentElement.style.display = 'none';
            document.querySelector(`.toggle-tracking[data-product-id="${productId}"]`).style
                .display = 'inline-block';
        });
    });
    function toggleTracking(storeId, productId, enable, toggleButton, minimumQuantityAlert = 0) {
        fetch(`/store/${storeId}/product/${productId}/toggle-tracking`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'),
            },
            body: JSON.stringify({
                enable_tracking: enable,
                minimum_quantity_alert: minimumQuantityAlert
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (toggleButton) {
                        const newAction = enable ? 'disable' : 'enable';
                        const newText = enable ? 'Vypnout' : 'Zapnout';
                        toggleButton.textContent = newText;
                        toggleButton.setAttribute('data-action', newAction);
                    }
                    showUniversalModal('Úspěch', 'Změna byla provedena', true);
                }
            })
            .catch(error => console.error('Error:', error));
    }
});







document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.remove-product').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const storeId = document.getElementById('storeIdForm')
                .value;

            fetch(`/store/${storeId}/remove-product/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]').getAttribute('content'),
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showUniversalModal('Úspěch', 'Změna byla provedena', true);

                    }
                })
                .catch(error => console.error('Error:', error));

        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.open-update-form').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            document.querySelector(`.update-quantity-form[data-product-id="${productId}"]`)
                .classList.remove('d-none');
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.open-update-form').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const formDiv = document.querySelector(
                `.update-quantity-form[data-product-id="${productId}"]`);
            formDiv.classList.remove('d-none');
            this.classList.add('d-none');
        });
    });

    document.querySelectorAll('.cancel-update').forEach(button => {
        button.addEventListener('click', function () {
            const formDiv = this.closest('.update-quantity-form');
            formDiv.classList.add('d-none');
            const productId = formDiv.getAttribute('data-product-id');
            document.querySelector(`.open-update-form[data-product-id="${productId}"]`)
                .classList.remove('d-none');
            formDiv.querySelector('form').reset();
        });
    });
});