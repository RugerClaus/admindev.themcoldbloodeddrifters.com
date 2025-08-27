<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BandMembers;

class UsersController extends Controller
{
    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required']
        ]);

        $user = User::findOrFail($request->id);
        
        $band_profile = BandMembers::where('user_id', $user->id)->first();
        if ($band_profile) {
            $band_profile->delete();
        }

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
            $user->must_change_password = true;
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
        $user->must_change_password = true;
        $user->permission_level = 'user';
        $user->save();

        if ($user->permission_level !== 'admin') {
            BandMembers::create([
                'user_id' => $user->id,
                'name' => $user->username,
                'instrument' => "Default instrument filler",
                'bio' => "Default bio",
                'portrait' => 'https://placehold.co/300x700',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User added successfully',
            'user' => $user->makeVisible('password')->toArray(),
        ]);
    }


    public function user_change_password(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        $user = auth()->user();

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'You cannot reuse your current password.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Password updated. Please log in again.');
    }

    public function must_change_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('status', 'Password updated successfully.');
    }

}
