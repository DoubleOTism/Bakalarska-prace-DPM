@extends('layouts.app')

@section('content')
<div class="container all-orders">
    <h1>Správa objednávek</h1>

    <form action="{{ route('orders.filter') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col">
                <input type="date" name="date" class="form-control" placeholder="Datum">
            </div>
            <div class="col">
                <input type="text" name="user" class="form-control" placeholder="Uživatel">
            </div>
            <div class="col">
                <select name="status" class="form-control">
                    <option value="">Všechny stavy</option>
                    <option value="pending">Čeká na vyřízení</option>
                    <option value="completed">Dokončeno</option>
                    <option value="cancelled">Zrušeno</option>
                </select>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Filtrovat</button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Uživatel</th>
                <th>Datum vytvoření</th>
                <th>Datum poslední změny</th>
                <th>Status</th>
                <th>Celková cena</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $order->updated_at->format('d.m.Y H:i') }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ number_format($order->total, 2, ',', ' ') }} Kč</td>
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
