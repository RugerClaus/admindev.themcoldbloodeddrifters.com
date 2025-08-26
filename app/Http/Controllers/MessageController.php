<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessages;


class MessageController extends Controller
{
    public function load_messages()
    {
        $messages = ContactMessages::orderBy('created_at','desc')->get();

        return response()->json($messages);
    }

    public function mark_message_as_read(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:contact_messages,id',
        ]);

        $message = ContactMessages::find($validated['id']);
        if ($message) {
            $message->read = true;
            $message->save();
        }

        return response()->json(['success' => true]);
    }
}
