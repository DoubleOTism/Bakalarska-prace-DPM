<!-- Modální Okno pro Obnovu Hesla -->
<div class="modal fade" id="passwordResetModal" tabindex="-1" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordResetModalLabel">Obnova hesla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="passwordResetForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mailová adresa</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="alert alert-danger d-none" id="passwordResetError"></div>
                    <button type="submit" class="btn btn-primary">Odeslat odkaz pro obnovu hesla</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modální Okno Úspěchu Odeslání E-mailu -->
<div class="modal fade" id="emailSentModal" tabindex="-1" aria-labelledby="emailSentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailSentModalLabel">Žádost Odeslána</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Pokud byl váš e-mail nalezen v naší databázi, obdržíte zprávu s odkazem na obnovu hesla.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('resources/js/modals/public/passwordReset.js') }}"></script>

