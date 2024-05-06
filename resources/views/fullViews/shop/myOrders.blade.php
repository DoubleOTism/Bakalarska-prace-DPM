@extends('layouts.app')

@section('content')
    <div class="container orders">
        <h1>Moje objednávky</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Č. objednávky</th>
                    <th>Celková cena</th>
                    <th>Status</th>
                    <th>Datum vytvoření</th>
                    <th>Datum poslední změny</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ number_format($order->total, 2, ',', ' ') }} Kč</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $order->updated_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <a href="{{ route('order.invoice.download', ['orderId' => $order->id]) }}"
                                class="btn btn-info">Stáhnout fakturu</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection


