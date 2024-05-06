document.addEventListener('DOMContentLoaded', () => {
    $('#passwordResetForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        $('#passwordResetError').addClass('d-none');

        $.ajax({
            type: 'POST',
            url: '/password/email',
            data: formData,
            success: function(response) {
                $('#passwordResetModal').modal('hide');
                $('#emailSentModal').modal(
                'show');
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var error = 'Došlo k neznámé chybě';


                if (response && response.errors) {
                    for (var key in response.errors) {
                        error = response.errors[key][
                        0];
                        break;
                    }
                } else if (response && response.message) {
                    error = response.message;
                }

                $('#passwordResetError').text(error).removeClass('d-none');
            }
        });

    });
});