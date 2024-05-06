@extends('layouts.app')

@section('content')
<div class="container review">
    <h1>Revize nákupu</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Produkt</th>
                <th>Cena za jednotku</th>
                <th>Množství</th>
                <th>Celkem</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart->items as $item)
                @php
                $finalPrice = $item->product->discount > 0 ? 
                              $item->product->price * (1 - $item->product->discount / 100) :
                              $item->product->price;
                @endphp
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ number_format($finalPrice, 2, ',', ' ') }} Kč</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->quantity * $finalPrice, 2, ',', ' ') }} Kč</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="total-price">
        <strong>Celková cena: {{ number_format($cart->items->sum(function ($item) {
            $finalPrice = $item->product->discount > 0 ? 
                          $item->product->price * (1 - $item->product->discount / 100) :
                          $item->product->price;
            return $item->quantity * $finalPrice;
        }), 2, ',', ' ') }} Kč</strong>
    </div>
    <div class="actions floating-buttons-container ">
        <button type="button" class="btn btn-secondary btn-rev" onclick="history.back();">Zpět do košíku</button>
        <button type="button" class="btn btn-danger btn-rev" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
            Zrušit objednávku
        </button>
        <form action="/completeCheckout" method="POST">
            @csrf
            <input type="hidden" name="sessionId" value="{{$sessionId}}">
            <button type="submit" class="btn btn-primary">Dokončit objednávku</button>
        </form>
    </div>
</div>

<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Zrušení objednávky</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zavřít"></button>
            </div>
            <div class="modal-body">
                <p>Doopravdy chcete zrušit objednávku?.</p>
                <p>Pokud si tak nepřejete, klikněte na tlačítko "Zpět".</p>
                <p>Pokud si přejete zrušit objednávku, prosím, klikněte na tlačítko "Zrušit objednávku", vraťte zboží zpět na své určené místo a opusťte co nejdříve prodejnu.</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('checkout.cancel') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="sessionId" value="{{$sessionId}}">
                    <button type="submit" class="btn btn-danger btn-rev">Zrušit objednávku</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zpět</button>
            </div>
        </div>
    </div>
</div>
@endsection
