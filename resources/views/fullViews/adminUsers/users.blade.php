@extends('layouts.app')

@section('content')
<div class="container overflow-y: auto; user">
    <h1>Uživatelé</h1>
    <div class="filters mb-3 d-flex align-items-end">
        <input type="text" id="searchFilter" class="form-control" placeholder="Vyhledat uživatele...">

        <select id="roleFilter" class="form-control">
            <option value="">Filtrovat dle role</option>
            @foreach ($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>

        <select id="statusFilter" class="form-control">
            <option value="">Filtrovat dle stavu</option>
            <option value="activated">Aktivní</option>
            <option value="unactivated">Neaktivní</option>
            <option value="stopped">Pozastaven</option>
        </select>
    </div>
    <table class="table" id="usersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Jméno</th>
                <th>Email</th>
                <th>Stav účtu</th>
                <th>Role</th>
                <th>Akce</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @switch($user->status)
                    @case('activated')
                    Aktivní
                    @break
                    @case('unactivated')
                    Neaktivní
                    @break
                    @case('stopped')
                    Pozastaven
                    @break
                    @default
                    Neznámý
                    @endswitch
                </td>
                <td>
                    @foreach ($user->roles as $role)
                    <span class="badge bg-secondary" data-toggle="tooltip" data-placement="top" title="{{ $role->description }}">
                        {{ $role->name }}
                    </span>
                    @endforeach
                </td>
                <td>
                    <form action="{{ route('admin.updateUserStatus', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $user->status === 'activated' ? 'btn-secondary' : 'btn-success' }}">
                            {{ $user->status === 'activated' ? 'Pozastavit' : 'Aktivovat' }}
                        </button>
                    </form>
                    <button class="btn btn-info btn-sm manage-roles" data-user-id="{{ $user->id }}">Spravovat role</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('modals.adminUsers.roleModal')
<script src="{{ asset('resources/js/fullViews/adminUsers/users.js') }}"></script>

@endsection