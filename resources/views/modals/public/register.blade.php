<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Registrace</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>

            <div class="modal-body">
                <form id="registerForm">
                    <div id="formErrors" class="alert alert-danger" style="display:none;"></div>
                    @csrf
                    <div class="form-group">
                        <label for="first_name">Křestní jméno</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Příjmení</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Adresa bydliště</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="city">Město</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                    <div class="form-group">
                        <label for="zip">PSČ</label>
                        <input type="text" class="form-control" id="zip" name="zip" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email_register" name="email" required
                            {{ session('email') ? 'value=' . session('email') . ' readonly' : '' }}>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefon</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    @if (!session('provider'))
                        <div class="form-group">
                            <label for="password">Heslo</label>
                            <input type="password" class="form-control" id="password_register" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Potvrzení hesla</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>
                    @endif
                    <input type="hidden" id="google_id" name="google_id" value="{{ session('google_id', '') }}">
                    <input type="hidden" id="facebook_id" name="facebook_id" value="{{ session('facebook_id', '') }}">
                    <input type="hidden" id="provider" value="{{ session('provider', '') }}">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="gdprProcessingConsent"
                            name="gdprProcessingConsent" required>
                        <label class="form-check-label" for="gdprProcessingConsent">
                            Souhlasím s <a href="/op"
                                target="_blank">obchodními podmínkami</a>.
                        </label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="dataProcessingConsent"
                            name="dataProcessingConsent" required>
                        <label class="form-check-label" for="dataProcessingConsent">
                            Souhlasím s <a href="/gdpr"
                            target="_blank">podmínkami ochrany osobních údajů</a>.
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Registrovat</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successMessageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p id="successMessage"></p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('resources/js/modals/public/register.js') }}"></script>

