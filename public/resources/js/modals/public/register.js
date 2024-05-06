document.addEventListener('DOMContentLoaded', function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();

        let errorMessages = '';

        let first_name = $('#first_name').val().trim();
        let last_name = $('#last_name').val().trim();
        let address = $('#address').val().trim();
        let city = $('#city').val().trim();
        let zip = $('#zip').val().trim();
        let email = $('#email_register').val().trim();
        let phone = $('#phone').val().trim();
        let password = $('#password_register').val();
        let password_confirmation = $('#password_confirmation').val();
        let facebook_id = $('#facebook_id').val().trim();
        let google_id = $('#google_id').val().trim();
        let provider = $('#provider').val();



        if (!first_name) errorMessages += '<p>Křestní jméno je povinné pole.</p>';
        if (!last_name) errorMessages += '<p>Příjmení je povinné pole</p>';
        if (!address) errorMessages += '<p>Adresa bydliště je povinné pole.</p>';
        if (!city) errorMessages += '<p>Město je povinné pole.</p>';
        if (!/^\d{5}$/.test(zip)) errorMessages += '<p>PSČ musí mít přesně 5 číslic.</p>';
        if (!/^\d{9}$/.test(phone)) errorMessages +=
            '<p>Telefon musí mít formát XXXXXXXXX.</p>';
        if (!/^[^@]+@[^@]+\.[^@]+$/.test(email)) errorMessages +=
            '<p>Email musí mít platný formát.</p>';
        if (!provider) {
            if (password.length < 8) errorMessages += '<p>Heslo musí mít více než 8 znaků.</p>';
            if (password !== password_confirmation) errorMessages += '<p>Hesla se neshodují.</p>';
        };

        if (errorMessages) {
            $('#formErrors').html(errorMessages).show();
        } else {
            $('#formErrors').hide();

            let formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '/register',
                data: formData,
                success: function(response) {
                    $('#registerModal').modal('hide');
                    $('#registerForm').trigger('reset');

                    $('#successMessageModal').modal('show');
                    $('#successMessage').html(
                        'Registrace proběhla úspěšně. Na váš email byl odeslán aktivační odkaz.<br>Nyní budete přesměrováni na hlavní stránku a přihlášeni jako neaktivní uživatel.'
                    );
                    setTimeout(function() {
                        window.location.href = '/';
                    }, 5000);

                },

                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    var message = response.message;

                    $('#formErrors').html('<p>Chyba registrace, ' + message + '</p>')
                        .show();
                }
            });
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('showRegister') === 'true') {
        $('#registerModal').modal('show');
    }
});