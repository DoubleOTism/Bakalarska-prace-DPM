@extends('layouts.app')

@section('content')
<div class="container mt-4 product">
    <div class="row">
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            @if ($product->discount > 0)
                <p class="price">
                    Cena se slevou ({{ $product->discount }}%): <span class="price-new">{{ number_format($product->price - ($product->price * ($product->discount / 100)), 2) }} Kč</span>
                    <small class="price-excl-tax">(Bez DPH: {{ number_format(($product->price - ($product->price * ($product->discount / 100))) / (1 + ($product->tax_rate / 100)), 2) }} Kč)</small>
                    Původní cena: <span class="price-old">{{ number_format($product->price, 2) }} Kč</span><small class="price-excl-tax"> (Bez DPH: {{ number_format($product->price_excl_tax, 2) }} Kč)</small><br>
                </p>
            @else
                <p class="price">
                    Cena s DPH: <span>{{ number_format($product->price, 2) }} Kč</span>
                    <small class="price-excl-tax">(Bez DPH: {{ number_format($product->price / (1 + ($product->tax_rate / 100)), 2) }} Kč)</small>
                </p>
            @endif
            <p class="price-details">Balení: {{ $product->unit }}</p>
            <p class="tax">Sazba DPH: {{ $product->tax_rate }}%</p>
            <p>{!! nl2br(e($product->description)) !!}</p>
        </div>
        <div class="col-md-6">
            <div id="productImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                @if ($product->photos->isNotEmpty())
                    <div class="carousel-inner">
                        @foreach ($product->photos as $index => $photo)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <img src="{{ asset($photo->path) }}" class="d-block w-100 img-fluid" alt="{{ $photo->alias }}">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productImagesCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                @else
                    <img src="{{ asset('\resources\images\na.png') }}" class="d-block w-100 img-fluid" alt="Default Image">
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

