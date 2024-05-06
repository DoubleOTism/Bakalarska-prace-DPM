@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nastavení Upozornění na Nízké Zásoby</h1>
    <form action="/settings/lowStockAlert/update" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">E-mail pro upozornění</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $low_stock_email) }}">
        </div>
        <div class="form-group">
            <label for="frequency">Frekvence (dny)</label>
            <input type="number" name="frequency" id="frequency" class="form-control" value="{{ old('frequency', $low_stock_frequency) }}">
        </div>
        <div class="form-group">
            <label for="time">Čas (hh:mm)</label>
            <input type="time" name="time" id="time" class="form-control" value="{{ old('time', $low_stock_time) }}">
        </div>
        <button type="submit" class="btn btn-primary">Uložit Nastavení</button>
    </form>

    <form action="/settings/lowStockAlert/test" method="POST" style="margin-top: 20px;">
        @csrf
        <button type="submit" class="btn btn-secondary">Odeslat Testovací Upozornění</button>
    </form>
</div>
@endsection
