document.addEventListener('DOMContentLoaded', function () {
    const qrCodeReader = new Html5Qrcode("qr-reader");
    const startScanButton = document.getElementById('startScan');
    const infoText = document.querySelectorAll('.info-text');
    const qrCodeDataElement = document.getElementById('qrCodeData');

    startScanButton.addEventListener('click', function () {
        startScanButton.style.display = 'none';

        infoText.forEach(function (textElement) {
            textElement.style.display = 'none';
        });

        qrCodeReader.start({
            facingMode: "environment"
        }, {
            fps: 20,
            qrbox: 250
        }, function (qrCodeMessage) {
            qrCodeReader.stop().then(() => {
                verifyQrCode(qrCodeMessage);
            }).catch(err => {
                console.error("Chyba při zastavování skenování", err);
            });
        }, function (errorMessage) {
        }).catch(err => {
            console.error("Nelze spustit skenování", err);
        });
    });
    function verifyQrCode(qrData) {
        fetch('/verify-qr-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content'),
            },
            body: JSON.stringify({
                qrContent: qrData
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayAccessCode(data.accessCode);
                    sessionStorage.setItem('store_id', data.store_id);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function displayAccessCode(code) {
        const accessCodeElement = document.getElementById('accessCode');
        const accessCodeDisplay = document.getElementById('accessCodeDisplay');
        const timeBar = document.getElementById('timeBar');
        const confirmButton = document.getElementById('confirmAccessPre');
        const denyButton = document.getElementById('denyAccess');
        const totalTime = 10000;
        const scanButton = document.getElementById('startScan');


        accessCodeElement.textContent = code;
        accessCodeDisplay.style.display = 'block';
        scanButton.style.display = 'none';
        let startTime = new Date();

        const interval = setInterval(() => {
            const elapsedTime = new Date() - startTime;
            const width = Math.min(100, (elapsedTime / totalTime) * 100);
            timeBar.style.width = width + '%';

            if (elapsedTime >= totalTime) {
                clearInterval(interval);
                timeBar.style.width = '0%';
                accessCodeDisplay.style.display = 'none';
                new bootstrap.Modal(document.getElementById('accessConfirmationModal')).show();
                scanButton.style.display = 'block';
            }
        }, 1000);
    }

    
    const accessModal = new bootstrap.Modal(document.getElementById('accessConfirmationModal'));

    document.body.addEventListener('click', function(event) {
        if (event.target.matches('.confirm-access')) {
            console.log('Tlačítko kliknuto:', event.target);

            fetch('/log-activity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    action: 'shop_entry_success',
                    details: {
                        message: 'User confirmed they entered the store.'
                    }
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(() => {
                accessModal.hide();
                redirectToShopping(); 
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });


    function redirectToShopping() {
        document.getElementById('hiddenStoreId').value = sessionStorage.getItem('store_id');

        document.getElementById('storeRedirectForm').submit();
    }

    document.getElementById('denyAccess').addEventListener('click', function () {
        fetch('/log-activity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                action: 'shop_entry_failed',
                details: {
                    message: 'User could not enter the store.'
                }
            })
        }).then(response => {
            document.getElementById('initialOptions').style.display = 'none';
            document.getElementById('additionalOptions').style.display = 'block';
        }).catch(error => {
            console.error('Error:', error);
        });
    });

    document.getElementById('retryScan').addEventListener('click', function () {
        fetch('/log-activity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                action: 'scan_qr_retry',
                details: {
                    message: 'User chose to retry scanning the QR code.'
                }
            })
        }).then(response => {
            location.reload()
        }).catch(error => {
            console.error('Error:', error);
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    
});
