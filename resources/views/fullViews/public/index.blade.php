@extends('layouts.app')

@section('content')
    <div class="container index">
        <div class="welcome-message">
            <h2 class="main-heading">Vítejte na webu mobilní aplikace společnosti Do psí misky</h2>
            <p>Pro ještě jednodušší nákupy jsme pro Vás připravili možnost nakupovat bez obsluhy a kdykoliv budete chtít.
                Stačí k tomu Váš telefon s kamerou, připojení k internetu a aktivní účet na našem portálu
                (registrovat/přihlásit se můžete skrze tlačítko vpravo nahoře).</p>
            <p>Následně si, prosím, přečtěte informace v záložce <a href="{{ route('how-it-works') }}">Jak to funguje?</a>.
            </p>
            <p><a href="/catalog">Zde</a> si jinak můžete zobrazit produkty, které jsou dostupné na samoobslužných
                prodejnách. Brzy bude
                implementována i mapa samoobslužných prodejen.</p>
        </div>

        <h3 class="carousel-heading">Veškeré zboží</h3>
        <div class="multiple-items all-products">
            @foreach ($products as $product)
                <div>
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ $product->photos->first()->path ?? 'path/to/default/image.jpg' }}"
                            alt="{{ $product->name }}">
                        <p>{{ $product->name }}</p>
                        @if ($product->discount > 0)
                            <p>
                                <del>{{ number_format($product->price, 2) }} Kč</del>
                                <strong>{{ number_format($product->price * (1 - $product->discount / 100), 2) }} Kč</strong>
                                <span class="badge bg-danger">{{ $product->discount }}% sleva</span>
                            </p>
                        @else
                            <p>{{ number_format($product->price, 2) }} Kč</p>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>


        @if ($products->where('discount', '>', 0)->count() > 0)
            <h3 class="carousel-heading">Zboží ve slevě</h3>
            <div class="multiple-items discounted-products">
                @foreach ($products->where('discount', '>', 0) as $product)
                    <div class="discounted">
                        <a href="{{ route('products.show', $product->id) }}">
                            <img src="{{ $product->photos->first()->path ?? 'path/to/default/image.jpg' }}"
                                alt="{{ $product->name }}">
                            <p>{{ $product->name }}</p>
                            <p>
                                <del>{{ number_format($product->price, 2) }} Kč</del>
                                <strong>{{ number_format($product->price * (1 - $product->discount / 100), 2) }}
                                    Kč</strong>
                                <span class="badge bg-danger">{{ $product->discount }}% sleva</span>
                            </p>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-discount-message-container">
                <div class="no-discount-message">
                    <p>V tento moment nejsou žádné produkty ve slevě, můžete si ale projít <a href="/catalog">katalog
                            zboží</a>.</p>
                </div>
            </div>
        @endif

        @if ($showScanButton)
            <a href="/scan-page" class="floating-button btn btn-primary" id="scanButton">
                <i class="fas fa-shopping-cart"></i>
            </a>
        @endif
    </div>

    <!-- Cookie Disclaimer -->
    <div id="cookieBanner"
        style="display:none; position: fixed; bottom: 0; width: 100%; background-color: #333; color: white; padding: 10px; text-align: center; z-index: 1000;">
        Tato stránka používá cookies, aby Vám poskytla lepší uživatelský zážitek. Pro více informací navštivte naši <a
            href="/gdpr" style="color: #f0ad4e; text-decoration: underline;">stránku GDPR</a>.
        <button id="acceptCookies"
            style="margin-left: 15px; background-color: #f0ad4e; border: none; color: black; padding: 5px 10px; cursor: pointer;">Přijmout</button>
    </div>


    <script src="{{ asset('resources/js/fullViews/public/index.js') }}"></script>
@endsection
