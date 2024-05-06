<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Přihlášení</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loginError" class="alert alert-danger" style="display: none;"></div>
                <form id="loginForm">
                    @if (request()->has('loginModal'))
                        <input type="hidden" name="loginModal" value="true">
                    @endif
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Emailová adresa</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Heslo</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Zapamatuj si mě
                            </label>
                        </div>
                    </div>
                    <div class="actions d-flex justify-content-between align-items-center mb-3">
                    <button type="submit" class="btn btn-primary">Přihlásit</button>
                    <button type="button" class="btn btn-warning" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#registerModal">Registrace</button>
                    
                    </div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#passwordResetModal">Zapomenuté heslo?</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('resources/js/modals/public/login.js') }}"></script>


