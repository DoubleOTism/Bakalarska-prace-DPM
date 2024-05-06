@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reset hesla</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email">E-mailová adresa</label>
            <input type="email" name="email" id="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Nové heslo</label>
            <input type="password" name="password" id="password" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password-confirm">Potvrzení hesla</label>
            <input type="password" name="password_confirmation" id="password-confirm" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Resetovat heslo</button>
    </form>
</div>
@endsection