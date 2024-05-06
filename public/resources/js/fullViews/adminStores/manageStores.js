document.addEventListener('DOMContentLoaded', function() {
    $('#addStoreForm').on('submit', function(e) {
        e.preventDefault();

        let formData = {
            'name': $('#storeName').val(),
            'description': $('#storeDescription').val(),
            'address': $('#storeAddress').val(),
            'status': 'enabled',
            '_token': $('input[name="_token"]').val()
        };

        $.ajax({
            type: 'POST',
            url: '/stores/create',
            data: formData,
            success: function(response) {
                $('#addStoreModal').modal('hide');
                showUniversalModal('Prodejna byla úspěšně přidána.');
                location.reload();
            },
            error: function(xhr) {
                alert('Něco se pokazilo.');
            }
        });
    });

    const viewQrModal = document.getElementById('viewQrModal');
    viewQrModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const storeId = button.getAttribute('data-store-id');
        document.getElementById('storeIdForm').value = storeId;
        fetchCurrentCode(storeId);
    });

    document.getElementById('showNewCodeFormBtn').addEventListener('click', function() {
        document.getElementById('newAccessCodeForm').style.display = 'block';
        this.style.display = 'none';
    });

    document.getElementById('newAccessCodeForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const storeId = document.getElementById('storeIdForm').value;
        const newAccessCode = document.getElementById('newAccessCode').value;
        requestNewQrCode(storeId);
    });

    document.getElementById('deactivateCodeBtn').addEventListener('click', function() {
        const storeId = document.getElementById('storeIdForm').value;
        deactivateCode(storeId);
    });

    function fetchCurrentCode(storeId) {
        fetch(`/store/${storeId}/current-code`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('showNewCodeFormBtn')
                        .click();
                } else {
                    document.getElementById('qrCodeDisplayStore').innerHTML =
                        `<img src="${data.qr_path}" style="cursor: pointer;" onclick="window.open('${data.qr_path}', '_blank');">`;
                    document.getElementById('accessCodeDisplayStore').textContent =
                        `Aktuální přístupový kód: ${data.code}`;
                    document.getElementById('deactivateCodeBtn').style.display = 'block';
                    $("#showNewCodeFormBtn").hide();
                }
            })
    }


    function requestNewQrCode(storeId) {
        const accessCode = document.getElementById('newAccessCode')
            .value; // Předpokládá, že máte input pro nový přístupový kód

        fetch(`/store/${storeId}/new-code`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    access_code: accessCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#viewQrModal').modal('hide');
                    document.getElementById('newAccessCodeForm').reset();
                    showUniversalModal('Úspěch', 'QR kód byl úspěšně vytvořen.', true);
                }
            })
    }

    document.getElementById('changeAccessCodeForm').addEventListener('submit', function(event) {
        event.preventDefault();

        // Tato část kódu získá storeId přímo z modálního okna, když je otevřeno
        var storeId = $('#changeAccessCodeModal').data(
        'store-id'); // Předpokládá, že storeId je nastaveno jako data atribut na modálním okně
        var newAccessCode = document.getElementById('newAccessCodeInput').value;

        fetch(`/store/${storeId}/update-access-code`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                },
                body: JSON.stringify({
                    newAccessCode: newAccessCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#changeAccessCodeModal').modal('hide');
                    showUniversalModal('Úspěch', 'Přístupový kód byl úspěšně změněn.', true);
                    document.getElementById('changeAccessCodeForm').reset();


                } else {
                    showUniversalModal('Chyba', 'Přístupový kód nebyl změněn, chyba:' + data.message, false);
                }
            });
    });

    // Při otevírání modálního okna se nastaví data-store-id do data atributu modálního okna
    $('#changeAccessCodeModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var storeId = button.data('store-id'); // Extract info from data-* attributes
        $(this).data('store-id', storeId); // Nastavení storeId do data atributu modálního okna
    });


    function deactivateCode(storeId) {
        fetch(`/store/${storeId}/deactivate-code`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#viewQrModal').modal('hide');
                    showUniversalModal('Úspěch', 'Přístupový kód byl úspěšně deaktivován.', true);
                    document.getElementById('qrCodeDisplay').innerHTML = '';
                    document.getElementById('accessCodeDisplay').textContent = '';
                    document.getElementById('deactivateCodeBtn').style.display = 'none';
                } else {
                    $('#viewQrModal').modal('hide');
                    showUniversalModal('Chyba', 'Něco se pokazilo:', data.error);
                }
            })

    }
    /* Povolení prodejny v DB */
    $('.btn-enable').click(function() {
        var storeId = $(this).data('id');
        $.ajax({
            url: '/stores/' + storeId + '/enable',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: storeId
            },
            success: function(response) {
                showUniversalModal('Úspěch', 'Prodejna byla povolena.', true);
                // Možná budete chtít aktualizovat UI, např. změnit stav tlačítek
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                showUniversalModal('Chyba', 'Nastala chyba při povolování prodejny.');
            }
        });
    });

    /* Zakázání prodejny v DB */
    $('.btn-disable').click(function() {
        var storeId = $(this).data('id');
        $.ajax({
            url: '/stores/' + storeId + '/disable',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: storeId
            },
            success: function(response) {
                showUniversalModal('Úspěch', 'Prodejna byla zakázána.', true);
                // Možná budete chtít aktualizovat UI
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                showUniversalModal('Chyba', 'Nastala chyba při zakazování prodejny.');
            }
        });
    });

    function filterStores() {
        var showInactive = $('#showInactiveStoresCheckbox').is(':checked');

        $('.store-row').each(function() {
            var isActive = $(this).data('active');
            if (showInactive || isActive == 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#showInactiveStoresCheckbox').change(function() {
        filterStores();
    });
    filterStores()
});