<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function init()
    {
        // Get the currently logged-in user
        $user = Auth::user();
        $users = User::all();

        $data = [
            'user' => $user,
            'users' => $users
        ];

        return view('dashboard', ['data' => $data]);
    }
}
