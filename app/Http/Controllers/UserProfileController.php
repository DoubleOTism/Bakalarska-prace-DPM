<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->only(['first_name', 'last_name', 'address', 'city', 'zip', 'phone']);
    
        $validationRules = [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'zip' => 'sometimes|required|string|max:5',
            'phone' => 'sometimes|required|string|max:9|unique:users,phone,' . $user->id,
        ];
    
        $request->validate(array_intersect_key($validationRules, $data));
        $user->update($data);
    
        return response()->json([
            'message' => 'Profil byl úspěšně aktualizován.',
            'user' => $user->only(['first_name', 'last_name', 'address', 'city', 'zip', 'phone']),
            'status' => 'success'
        ]);
    }

}
