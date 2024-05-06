<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Faktura Objednávka #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
        }
        .header {
            width: 100%;
            text-align: center;
        }
        .section {
            margin-bottom: 20px;
        }
        .left-column {
            float: left;
            width: 50%;
        }
        .right-column {
            float: right;
            width: 50%;
        }
        .clear {
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
        }
        .signature {
            text-align: right;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Faktura - daňový doklad</h1>
    </div>

    <div class="section">
        <div class="left-column">
            <h2>Dodavatel</h2>
            <p>Do psí misky s.r.o.</p>
            <p>Láz 266, 262 41, Bohutín</p>
            <p>IČO: 06183816</p>
            <p>DIČ: CZ06183816</p>
        </div>

        <div class="right-column">
            <h2>Odběratel</h2>
            <p>{{ $order->user->first_name }} {{ $order->user->last_name }}</p>
            <p>{{ $order->user->address }}</p>
            <p>{{ $order->user->city }}, {{ $order->user->zip }}</p>
            <p>Tel: {{ $order->user->phone }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section">
        <p>Objednávka #{{ $order->id }}</p>
        <p>Způsob platby: Platební brána GoPay</p>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Množství</th>
                    <th>Kč/ks bez DPH</th>
                    <th>DPH</th>
                    <th>Kč/ks s DPH</th>
                    <th>Sleva</th>
                    <th>Celkem</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    @php
                    $priceExclTax = $item->product->price / (1 + $item->product->tax_rate / 100);
                    $discountAmount = $item->product->price - $item->discounted_price;
                    $totalPriceExclTax = $item->quantity * $priceExclTax;
                    $totalDiscountedPrice = $item->quantity * $item->discounted_price;
                    @endphp
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($priceExclTax, 2, ',', ' ') }} Kč</td>
                        <td>{{ $item->product->tax_rate }}%</td>
                        <td>{{ number_format($item->product->price, 2, ',', ' ') }} Kč</td>
                        <td>{{ number_format($item->discount_rate) }}%</td>
                        <td>{{ number_format($totalDiscountedPrice, 2, ',', ' ') }} Kč</td>
                    </tr>
                @endforeach
            </tbody>           
        </table>
    </div>
    <div class="signature">
        <p>Celkem k úhradě: {{ number_format($order->total, 2, ',', ' ') }} Kč</p>
    </div>


</body>
</html>
