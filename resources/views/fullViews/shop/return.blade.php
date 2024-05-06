@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Výsledek platby</h1>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <a href="{{ url('/') }}" class="btn btn-primary">Zpět na hlavní stránku</a>
</div>
@endsection
