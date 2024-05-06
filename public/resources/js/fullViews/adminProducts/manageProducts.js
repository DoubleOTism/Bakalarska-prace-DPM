document.addEventListener('DOMContentLoaded', function () {
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip(); // Aktivuje tooltipy na všech prvcích s atributem data-toggle="tooltip"
    });



    /* Funkce pro nacteni fotografii - Zobrazeno při zobrazení okna pro editaci fotografií*/
    function loadPhotos(aliasFilter = '') {
        $.ajax({
            url: '/listPhotos',
            type: 'GET',
            data: {
                alias: aliasFilter
            },
            success: function (response) {
                const tbody = $('#photosTable tbody');
                tbody.empty();
                response.photos.forEach(photo => {
                    const row = `
                <tr id="photoRow${photo.id}">
                    <td><img src="${photo.url}" alt="${photo.alias}" class="img-thumbnail" width="100"></td>
                    <td>${photo.alias}</td>
                    <td>
                        <input class="form-check-input" type="checkbox" value="${photo.id}" id="photo${photo.id}">
                        <button class="btn btn-sm btn-primary edit-alias" data-photo-id="${photo.id}"><i class="fas fa-pencil-alt"></i></button>
                    </td>
                </tr>
            `;
                    tbody.append(row);
                });
            },
            error: function (xhr) {
                showUniversalModal('Chyba', 'Došlo k chybě při načítání fotografií.', false);
            }
        });
    }

    /* Funkce pro nacteni fotografii - zobrazeno při přidání produktu*/
    function loadAvailablePhotos(searchTerm = '') {
        $.ajax({
            url: '/listPhotos',
            type: 'GET',
            data: { alias: searchTerm },
            success: function (data) {
                var gallery = $('#photoGallery');
                gallery.empty();

                data.photos.forEach(function (photo) {
                    var photoRow = `
                        <tr>
                            <td><img src="${photo.url}" alt="${photo.alias}" style="width: 50px; height: 50px;"></td>
                            <td>${photo.alias}</td>
                            <td>
                                <input class="form-check-input" type="checkbox" name="photo_ids[]" value="${photo.id}">
                            </td>
                        </tr>
                    `;
                    gallery.append(photoRow);
                });
            },
            error: function (xhr) {
                showUniversalModal('Chyba', 'Došlo k chybě při načítání fotografií.', false);
            }
        });
    }

    /* Funkce pro nacteni fotografii - Zobrazeno při editaci produktů*/
    function loadProductPhotos(productId, searchTerm = '') {
        $.ajax({
            url: '/listPhotos',
            type: 'GET',
            data: {
                productId: productId,
                alias: searchTerm
            },
            success: function (response) {
                var gallery = $('#editPhotoGallery');
                gallery.empty();

                response.photos.forEach(function (photo) {
                    var row = `<tr>
                        <td><img src="${photo.url}" style="width: 50px;"></td>
                        <td>${photo.alias}</td>
                        <td><input type="checkbox" name="photo_ids[]" value="${photo.id}" ${photo.selected ? 'checked' : ''}></td>
                    </tr>`;
                    gallery.append(row);
                });
            },
            error: function (xhr) {
                console.error('Došlo k chybě při načítání fotografií.');
            }
        });
    }

    /* Zpracování přidání produktu*/
    $('#addProductForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $('#selectedPhotos option:selected').each(function () {
            formData.append('photo_ids[]', $(this).val());
        });

        $.ajax({
            type: 'POST',
            url: '/products/store',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#addProductModal').modal('hide');
                showUniversalModal('Úspěch', 'Produkt byl úspěšně přidán.', true);
                document.getElementById("addProductForm").reset();

            },
            error: function (xhr, status, error) {
                $('#addProductModal').modal('hide');
                showUniversalModal('Chyba', 'Došlo k chybě: ' + error + ' \nServer xhr:' + xhr.responseText, false);
            }
        });
    });

    /* Změna aliasu fotky*/
    $(document).on('click', '.edit-alias', function () {
        let photoId = $(this).data('photo-id');
        let currentAlias = $(`label[for='photo${photoId}']`).text();
        let newAlias = prompt('Zadejte nový alias pro fotku:', currentAlias);

        if (newAlias && newAlias !== currentAlias) {
            $.ajax({
                url: `/updatePhotos/${photoId}`,
                type: 'POST',
                data: {
                    alias: newAlias,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $(`label[for='photo${photoId}']`).text(newAlias);
                    showUniversalModal('Úspěch', 'Alias byl úspěšně změněn.', true);
                },
                error: function () {
                    showUniversalModal('Chyba', 'Alias fotky nebyl změněn.', false);
                }
            });
        }
    });

    /* Smazání fotografie z databáze a její odebrání ze serveru. Pokud je obrázek používán, je zamezeno jeho smazání*/
    $('#deleteSelectedPhotos').click(function () {
        let selectedPhotos = $('input[type="checkbox"]:checked').map(function () {
            return $(this).val();
        }).get().filter(function (value) {
            return value !== "";
        });

        if (selectedPhotos.length > 0) {
            $.ajax({
                url: '/deletePhotos',
                type: 'POST',
                data: {
                    photos: selectedPhotos,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    response.deletedPhotos.forEach(function (photoId) {
                        $('#photo-container-' + photoId)
                            .remove();
                    });

                    if (response.dependentProducts) {
                        $('#dependentProducts').text(response.dependentProducts.join(', '));
                        $('#photoDependencyModal').modal('show');
                    } else {
                        loadPhotos();
                        showUniversalModal('Úspěch', 'Vybrané fotky byly odstraněny', false);

                    }
                },
                error: function (xhr) {
                    loadPhotos();
                    showUniversalModal('Chyba', 'Některé fotky byly odstraněny, ty které jsou stále spojeny s některým zboží nebyly smazány.', false);
                }
            });
        } else {
            showUniversalModal('Info', 'Nebyly vybrané fotografie pro smazání', false);
        }
    });

    /* Zpracování nahrání obrázku produktu*/
    $('#uploadImagesForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let images = document.getElementById('imagesInput').files;

        Array.from(images).forEach((image, index) => {
            formData.append(`images[${index}]`, image);
            $('#progressBarsContainer').append(`
        <div class="progress mb-2">
            <div id="progressBar${index}" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">${image.name}</div>
        </div>
    `);
        });
        $.ajax({
            url: '/uploadPhotos',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    Array.from(images).forEach((image, index) => {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt
                                .total;
                            percentComplete = parseInt(percentComplete *
                                100);
                            $(`#progressBar${index}`).css('width',
                                percentComplete + '%').attr(
                                    'aria-valuenow', percentComplete)
                                .text(percentComplete + '%');
                        }
                    });
                }, false);
                return xhr;
            },
            success: function (response) {
                $('#uploadImagesForm').trigger('reset');
                $('#uploadImagesModal').modal('hide');
                var uploadedCount = response.uploaded.length;
                var skippedCount = response.skipped.length;
                var message = `Úspěšně nahráno ${uploadedCount} fotek.`;
                if (skippedCount > 0) {
                    message +=
                        ` Následující fotky byly přeskočeny kvůli duplicitě názvů: ${response.skipped.join(', ')}.`;
                }
                $('#confirmationModalBody').html(message);
                $('#confirmationModal').modal('show');
            },
            error: function (xhr) {
                var errorMessage = 'Došlo k chybě při nahrávání obrázků.' + xhr;
                $('#confirmationModalBody').text(errorMessage);
                $('#confirmationModal').modal('show');
            }
        });
    });

    /* Načtení informací o produktu při zmáčknutí tlačítka pro jeho editaci*/
    $('.btn-edit').on('click', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var description = $(this).data('description');
        var unit = $(this).data('unit');
        var price = $(this).data('price');
        var discount = $(this).data('discount');
        var barcode = $(this).data('barcode');
        var taxRate = $(this).data('taxrate');
        var priceExcludingTax = $(this).data('priceexcltax');
        console.log(priceExcludingTax);
        console.log(taxRate);

        $('#editProductId').val(id);
        $('#editProductName').val(name);
        $('#editProductDescription').val(description);
        $('#editProductUnit').val(unit);
        $('#editProductPrice').val(price);
        $('#editProductDiscount').val(discount);
        $('#editProductCode').val(barcode);
        $('#editProductTaxRate').val(taxRate);
        $('#editProductPriceExcludingTax').val(priceExcludingTax);

    });


    /* Zpracování úpravy produktu*/
    $('#editProductForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $('input[name="photo_ids[]"]:checked').each(function () {
            formData.append('photo_ids[]', $(this).val());
        });

$.ajax({
    url: '/products/update',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
        $('#editProductModal').modal('hide');
        showUniversalModal('Úspěch', 'Zboží bylo úspěšně upraveno.', true);
        document.getElementById("editProductForm").reset();
    },
    error: function (xhr, status, errors) {
        let errorMessage = 'Neznámá chyba';
        
        try {
            const responseJSON = JSON.parse(xhr.responseText);
            if (responseJSON.message && responseJSON.error) {
                errorMessage = responseJSON.message + ': ' + responseJSON.error;
            } else if (responseJSON.message) {
                errorMessage = responseJSON.message;
            } else {
                errorMessage = xhr.responseText;
            }
        } catch (e) {
            errorMessage = xhr.responseText;
        }

        showUniversalModal('Chyba', 'Došlo k chybě, zboží nebylo upraveno. \n Chyba: ' + errorMessage + '\n Status: ' + status, false);
    }
});

    });

    /* Tlačítko pro zobrazení skrytého popisu*/
    document.querySelectorAll('.show-description-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const tr = this.closest('tr');
            tr.querySelector('.description-preview').classList.add('d-none');
            tr.querySelector('.full-description').classList.remove('d-none');
            this.classList.add('d-none');
            tr.querySelector('.hide-description-btn').classList.remove(
                'd-none');
        });
    });

    /* Tlačítko pro skrytí popisu*/
    document.querySelectorAll('.hide-description-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const tr = this.closest('tr');
            tr.querySelector('.description-preview').classList.remove('d-none');
            tr.querySelector('.full-description').classList.add('d-none');
            this.classList.add('d-none');
            tr.querySelector('.show-description-btn').classList.remove(
                'd-none');
        });
    });
    $('.btn-set-discount').on('click', function () {
        const productId = $(this).data('id');
        $('#discountProductId').val(productId);
    });

    /* Zpracování přidání slevy*/

    $('#setDiscountForm').on('submit', function (e) {
        e.preventDefault();

        let formData = {
            product_id: $('#discountProductId').val(),
            discount_amount: $('#discountAmount').val(),
            discount_from: $('#discountFrom').val(),
            discount_to: $('#discountTo').val(),
            _token: $('input[name="_token"]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/products/setDiscount', // URL backend metody pro nastavení slevy
            data: formData,
            success: function (response) {
                // Zavření modálního okna a zobrazení zprávy
                $('#setDiscountModal').modal('hide');
                showUniversalModal('Úspěch', 'Sleva byla nastavena.', true);
            },
            error: function (xhr, status, error) {
                // Zobrazení chyby
                console.error('Chyba: ', xhr.responseText);
                showUniversalModal('Chyba', 'Došlo k chybě při nastavení slevy.', true);
            }
        });
    });


    $(document).on('click', '.delete-discount', function () {
        var productId = $(this).data('product-id');

        $.ajax({
            url: '/products/' + productId + '/delete-discount',
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                showUniversalModal('Úspěch', 'Sleva byla odstraněna.', true);
                // Zde můžete aktualizovat UI nebo přesměrovat uživatele
            },
            error: function (xhr) {
                showUniversalModal('Chyba', 'Došlo k chybě při nastavení slevy.', true);
            }
        });
    });




    /* Povolení produktu v DB*/
    $('.btn-enable').click(function () {
        var productId = $(this).data('id');
        $.ajax({
            url: '/products/' + productId + '/enable',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr(
                    'content'),
                id: productId
            },
            success: function (response) {
                showUniversalModal('Úspěch', 'Produkt byl povolen.', true);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                showUniversalModal('Chyba', 'Nastala chyba při povolování produktu.');
            }
        });
    });

    /* Zakázání produktu v DB*/
    $('.btn-disable').click(function () {
        var productId = $(this).data('id');
        $.ajax({
            url: '/products/' + productId + '/disable',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr(
                    'content'),
                id: productId
            },
            success: function (response) {
                showUniversalModal('Úspěch', 'Produkt byl zakázán.', true);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                showUniversalModal('Chyba', 'Nastala chyba při zakazování produktu.');
            }
        });
    });

    /* Filtorvání aktivních nebo neaktivních produktů*/
    function filterProducts() {
        var showInactive = $('#showInactiveProductsCheckbox').is(':checked');

        $('.product-row').each(function () {
            var isActive = $(this).data(
                'active');
            if (showInactive || isActive) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }




    $('#editProductModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('id');
        loadProductPhotos(productId);
    });

    $('#photoSearch').on('keyup', function () {
        var searchTerm = $(this).val();
        loadAvailablePhotos(searchTerm);
        loadPhotos(searchTerm);
    });

    $('#searchPhotoAlias').on('keyup', function () {
        var aliasFilter = $(this).val();
        loadPhotos(aliasFilter);
    });

    $('#managePhotosModal').on('shown.bs.modal', function () {
        loadPhotos();
    });

    loadPhotos();
    loadAvailablePhotos();

    filterProducts();

    $('#showInactiveProductsCheckbox').change(function () {
        filterProducts();
    });



});

























