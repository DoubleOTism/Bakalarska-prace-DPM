<!-- Choose Login Method Modal -->
<div class="modal fade" id="chooseLoginModal" tabindex="-1" aria-labelledby="chooseLoginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chooseLoginModalLabel">Vyberte způsob přihlášení</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Vyberte možnost přihlášení. Pokud vyberete možnost skrze Facebook či Google, bude následně potřeba
                    doplnit vaše údaje pro dokončení registrace.</p>
<button class="btn btn-primary w-100 mb-2 google-btn"
    onclick="window.location='{{ route('social.login', ['provider' => 'google']) }}'">
    <i class="fab fa-google"></i> Přihlásit se skrze Google
</button>
<button class="btn btn-primary w-100 mb-2 facebook-btn"
    onclick="window.location='{{ route('social.login', ['provider' => 'facebook']) }}'">
    <i class="fab fa-facebook-f"></i> Přihlásit se skrze Facebook
</button>
<button type="button" class="btn btn-primary w-100 mb-2 internal-btn" data-bs-dismiss="modal" data-bs-toggle="modal"
    data-bs-target="#loginModal">Přihlásit se skrze účet aplikace</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('resources/js/modals/public/choose.js') }}"></script>

