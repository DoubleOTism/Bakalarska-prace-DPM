<header>
    <div class="header-banner">
        @if (!request()->is('shopping'))
            <div class="left-section">
                <a href="{{ url('/') }}"><img src="{{ asset('resources/images/logo_dopsimisky.png') }}" alt="Logo" class="header-logo"></a>
            </div>
            <div class="right-section">
                @guest
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#chooseLoginModal">Přihlásit se</button>
                @else
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->first_name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userMenuButton">
                            <li>
                                @if (Auth::check() && Auth::user()->hasRole('Uživatel'))
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#userProfileModal">Profil</a>
                                @endif
                            </li>
                            <li>
                                @if (Auth::check() && Auth::user()->hasRole('Uživatel'))
                                    <a class="dropdown-item" href="/my-orders">Historie nákupů</a>
                                @endif
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                @if (Auth::check() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('A: Zboží')))
                                    <a class="dropdown-item" href="/manageProducts">Správa zboží</a>
                                @endif
                            </li>
                            <li>
                                @if (Auth::check() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('A: Prodejen')))
                                    <a class="dropdown-item" href="/manageStores">Správa prodejen</a>
                                @endif
                            </li>
                            <li>
                                @if (Auth::check() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('A: Uživatelů')))
                                    <a class="dropdown-item" href="/users">Správa uživatelů</a>
                                @endif
                            </li>
                            <li>
                                @if (Auth::check() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('A: Aplikace')))
                                    <a class="dropdown-item" href="/settings">Správa aplikace</a>
                                @endif
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Odhlásit se</a></li>
                        </ul>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endguest
            </div>
        @endif
    </div>

    @if (!request()->is('shopping'))
        <nav class="navbar navbar-expand-lg custom-navbar">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/catalog">Zboží</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/how-it-works">Jak to funguje?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/op">Obchodní podmínky</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/gdpr">GDPR</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contacts">Kontakty</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endif
</header>
