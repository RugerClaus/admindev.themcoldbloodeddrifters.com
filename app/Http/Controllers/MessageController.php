<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class MessageController extends Controller
{
    public function load_messages()
    {
        $messages = ContactMessages::all();

        return response()->json([
            'messages' => $messages
        ]);
    }
}
