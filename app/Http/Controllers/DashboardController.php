<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ContactMessages;
use App\Models\BandMembers;
use App\Models\Band;
use App\Models\CarouselImages;

class DashboardController extends Controller
{
    public function init()
    {
        // Get the currently logged-in user
        $user = Auth::user();
        $users = User::all();
        $messages = ContactMessages::orderBy('id','desc')->get();
        $unread_count = ContactMessages::where('read', false)->count();
        $bio = BandMembers::where('user_id',$user->id)->first();
        $band = Band::firstOrFail();

        

        $data = [
            'user' => $user,
            'users' => $users,
            'messages' => $messages,
            'bio' => $bio,
            'band' => $band,
            'unread_count' => $unread_count
        ];

        return view('dashboard', ['data' => $data]);
    }

    public function change_password()
    {
        return view('change_password');
    }
}
