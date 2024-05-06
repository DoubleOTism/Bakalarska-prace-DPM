document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const loginModal = urlParams.get('loginModal');

    if (loginModal === 'true') {
        const modal = new bootstrap.Modal(document.getElementById('chooseLoginModal'));
        modal.show();
    }
});