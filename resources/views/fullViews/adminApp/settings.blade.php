@extends('layouts.app')

@section('content')
<div class="container settings">
    <h1>Nastavení Aplikace</h1>
    <ul>
        <li><a href="/settings/lowStock">Nastavení upozornění na nízké zásoby</a></li>
        <li><a href="/allOrders">Všechny provedené objednávky</a></li>   
    </ul>
</div>
@endsection
