document.addEventListener('DOMContentLoaded', function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        var loginUrl = '/login';
        if (window.location.search.indexOf('loginModal=true') !== -1) {
            loginUrl += '?loginModal=true';
        }

        $.ajax({
            type: 'POST',
            url: loginUrl,
            data: formData,
            success: function(response) {

                if (window.location.search.indexOf('loginModal=true') !== -1) {
                    window.location.href = '/?activated=true';
                } else {
                    $('#loginModal').modal('hide');
                    window.location.reload();
                }
            },
            error: function(xhr) {
                $('#loginError').text('Přihlášení selhalo: ' + xhr.responseJSON.message)
                    .show();
            }
        });
    });
});