@extends('layouts.app')

@section('content')
    <div class="container py-5 scanQr">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Vstup do prodejny</h1>
                <p class="text-center info-text">
                    Pro vstup do prodejny je potřeba naskenovat QR kód umístěný u prodejny. Stiskněte tlačítko pro skenování
                    a namiřte fotoaparát Vašeho telefonu na QR kód. V případě požádání o potvrzení přístupu k fotoaparátu,
                    vyberte "Ano".
                </p>
                <p class="text-center info-text">
                    Pokud jste si ještě nepřečetli informace o tom, jak proces nákupu probíhá, doporučujeme si přečíst sekci
                    <a href="/how-it-works">Jak na to?</a>.
                </p>
                <div id="qr-reader" style="width:100%; height: auto;"></div>
                <button class="btn btn-primary mt-3 w-100" id="startScan">Naskenovat</button>
                <div id="accessCodeDisplay"
                    style="display: none; background-color: black; color: white; padding: 20px; text-align: center; position: relative;">
                    <p>Přístupový kód: <span id="accessCode" style="font-size: 2em;"></span></p>
                    <div id="timeBar" style="height: 5px; background-color: red; width: 0%;"></div>
                    <button class="btn btn-warning mt-3 confirm-access" id="confirmAccess">Zahájit nákup</button>
                </div>
            </div>

        </div>
    </div>
    @include('modals.shop.confirmAccessModal')

    <script src="{{ asset('resources/js/fullViews/requirements/html5-qrcode.min.js') }}"></script>
    <script src="{{ asset('resources/js/fullViews/shop/scanQr.js') }}"></script>
@endsection
