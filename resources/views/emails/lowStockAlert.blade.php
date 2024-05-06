<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Upozornění na Nízké Zásoby</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Upozornění na Nízké Zásoby</h1>
        <p>Následující produkty mají nízké zásoby:</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produkt</th>
                <th>Prodejna</th>
                <th>Dostupné množství</th>
                <th>Minimální množství</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                @foreach($product->stores as $store)
                    @if ($store->pivot->quantity <= $store->pivot->minimum_quantity_alert)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $store->name }}</td>
                            <td>{{ $store->pivot->quantity }}</td>
                            <td>{{ $store->pivot->minimum_quantity_alert }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
