function showUniversalModal(title, message, shouldReload = false) {
    const modalBody = document.getElementById('universalModalBody');
    modalBody.innerHTML = message;
    const modalLabel = document.getElementById('universalModalLabel');
    modalLabel.innerHTML = title;

    const universalModal = new bootstrap.Modal(document.getElementById('universalModal'), {
        keyboard: true
    });
    universalModal.show();
    if (shouldReload) {
        setTimeout(function () {
            window.location.reload();
        }, 1500);
    }
}




