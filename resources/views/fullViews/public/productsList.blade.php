@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Katalog Produktů</h1>
    <div class="row">
        @foreach ($products as $product)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm" onclick="location.href='{{ route('products.show', $product->id) }}'" style="cursor: pointer;">
                @if ($product->photos->isNotEmpty())
                    <img src="{{ $product->photos->first()->path }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                    <img src="\resources\images\na.png" class="card-img-top" alt="Default Image">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    @if ($product->discount > 0)
                        <p class="text-danger"><del>{{ number_format($product->price, 2) }} Kč</del> {{ number_format($product->price * (1 - $product->discount / 100), 2) }} Kč</p>
                    @else
                        <p>{{ number_format($product->price, 2) }} Kč</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">
            {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
