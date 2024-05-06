<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $users = User::with('roles')
            ->where('first_name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->paginate(10);

        $roles = Role::all();

        return view('fullViews/adminUsers/users', compact('users', 'search', 'roles'));
    }
    public function updateStatus(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        switch ($user->status) {
            case 'activated':
                $user->status = 'stopped';
                break;
            case 'stopped':
            case 'unactivated':
                $user->status = 'activated';
                break;
        }
        $user->save();

        return back()->with('success', 'Stav uživatele byl aktualizován.');
    }
    public function updateRoles(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $roles = $request->input('roles', []);
        $user->roles()->sync($roles);  // Přiřaďte role k uživateli

        return response()->json(['success' => true]);
    }

    public function getUserRoles($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $allRoles = Role::all();
        $assignedRoles = $user->roles->pluck('id')->toArray();

        return response()->json([
            'allRoles' => $allRoles,
            'assignedRoles' => $assignedRoles
        ]);
    }
    public function filter(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->get();

        return response()->json(['users' => $users]);
    }
}
