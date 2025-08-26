<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ContactMessages;

class DashboardController extends Controller
{
    public function init()
    {
        // Get the currently logged-in user
        $user = Auth::user();
        $users = User::all();
        $messages = ContactMessages::orderBy('id','desc')->get();
        

        $data = [
            'user' => $user,
            'users' => $users,
            'messages' => $messages
        ];

        return view('dashboard', ['data' => $data]);
    }

    public function change_password()
    {
        return view('change_password');
    }
}
