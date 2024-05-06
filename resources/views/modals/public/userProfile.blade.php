<div class="modal fade" id="userProfileModal" tabindex="-1" aria-labelledby="userProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userProfileModalLabel">Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                @auth
                <div class="mb-3">
                    <p><strong>Status účtu:</strong> <span id="userStatus">{{ Auth::user()->status }}</span>
                        <span class="status-bubble" id="statusBubble"></span>
                    </p>
                </div>
                <div class="mb-3">
                    <p><strong>Jméno:</strong> {{ Auth::user()->first_name }}</p>
                </div>
                <div class="mb-3">
                    <p><strong>Příjmení:</strong> {{ Auth::user()->last_name }}</p>
                </div>
                <div class="mb-3">
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                </div>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <p class="mb-0 user-phone"><strong>Telefon:</strong> {{ Auth::user()->phone }}</p>
                    <button class="btn btn-outline-secondary btn-sm ms-2 edit-button" data-bs-toggle="modal" data-bs-target="#editModal" data-type="phone" data-value="{{ Auth::user()->phone }}"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <p class="mb-0 user-address"><strong>Adresa:</strong> {{ Auth::user()->address }}</p>
                    <button class="btn btn-outline-secondary btn-sm ms-2 edit-button" data-bs-toggle="modal" data-bs-target="#editModal" data-type="address" data-value="{{ Auth::user()->address }}"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <p class="mb-0 user-city"><strong>Město:</strong> {{ Auth::user()->city }}</p>
                    <button class="btn btn-outline-secondary btn-sm ms-2 edit-button" data-bs-toggle="modal" data-bs-target="#editModal" data-type="city" data-value="{{ Auth::user()->city }}"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <p class="mb-0 user-zip"><strong>PSČ:</strong> {{ Auth::user()->zip }}</p>
                    <button class="btn btn-outline-secondary btn-sm ms-2 edit-button" data-bs-toggle="modal" data-bs-target="#editModal" data-type="zip" data-value="{{ Auth::user()->zip }}"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div>
                    <p><strong>Propojené Účty:</strong></p>
                    <ul>
                        @if (Auth::user()->google_id)
                        <li>Google</li>
                        @endif
                        @if (Auth::user()->facebook_id)
                        <li>Facebook</li>
                        @endif
                    </ul>
                </div>
                <div>
                    <p><strong>Role:</strong></p>
                    <ul>
                        @foreach(Auth::user()->roles as $role)
                        <li>{{ $role->name }}</li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p>Pro zobrazení informací o profilu se prosím přihlaste.</p>
                @endauth
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editace</h5>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="editType" name="type">
                    <div class="mb-3">
                        <label for="editValue" class="form-label">Nová Hodnota</label>
                        <input type="text" class="form-control" id="editValue" name="value">
                    </div>
                    <button type="submit" class="btn btn-primary">Uložit Změny</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#userProfileModal">Zavřít</button>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('resources/js/modals/public/userProfile.js') }}"></script>
