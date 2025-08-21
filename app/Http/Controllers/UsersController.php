<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required']
        ]);

        $user = User::findOrFail($request->id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);

    }

    public function update(Request $request)
    {
        $request->validate([
            'id'       => ['required', 'integer', 'exists:users,id'],
            'username' => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        $user = User::findOrFail($request->id);

        if ((int) $user->id === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Admin user cannot be modified this way',
            ], 403);
        }

        $user->username = $request->username;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user'    => $user,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'User added successfully',
            'user' => $user->makeVisible('password')->toArray(),
        ]);
    }

}
