document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('activated') === 'true') {
        new bootstrap.Modal(document.getElementById('accountActivatedModal')).show();
        setTimeout(function() {
            window.location.href = '/';
        }, 5000);
    }
});