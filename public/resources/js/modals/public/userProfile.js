document.addEventListener('DOMContentLoaded', function() {
    $('.edit-button').on('click', function() {
        const type = $(this).data('type');
        const currentValue = $(this).data('value');
        $('#editType').val(type);
        $('#editValue').val(currentValue);
        $('#editForm').attr('action', `/profile/update/${type}`);
        $('#editModal').modal('show');
    });

    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('_token', $('input[name="_token"]').val());
        formData.append('_method', 'PUT');

        let type = $('#editType').val();
        let value = $('#editValue').val();

        formData.append(type, value);

        $.ajax({
            url: '/profile/update',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('#editModal .modal-body').prepend(
                        '<div class="alert alert-success">' + response.message +
                        '</div>');

                    Object.keys(response.user).forEach(key => {
                        $('.user-' + key).text(key.charAt(0).toUpperCase() + key
                            .slice(1) + ': ' + response.user[key]);
                    });

                    setTimeout(() => {
                        $('.alert-success').remove();
                        $('#editModal').modal('hide');
                        $('#userProfileModal').modal('show')
                    }, 3000);
                }
            },
            error: function(xhr) {
                $('#editModal .modal-body').prepend(
                    '<div class="alert alert-danger">Došlo k chybě při aktualizaci údaje.</div>'
                );

                setTimeout(() => {
                    $('.alert-danger').remove();
                }, 3000);
            }
        });
    });
    $(document).ready(function() {
        var status = $('#userStatus').text();

        var bubbleClass;
        switch (status) {
            case 'activated':
                bubbleClass = 'activated';
                $('#userStatus').text('Aktivní');
                break;
            case 'unactivated':
                bubbleClass = 'unactivated';
                $('#userStatus').text('Neaktivní');
                break;
            case 'stopped':
                bubbleClass = 'stopped';
                $('#userStatus').text('Pozastaven');
                break;
            default:
                bubbleClass = '';
                break;
        }

        $('#statusBubble').addClass(bubbleClass);
    });
});