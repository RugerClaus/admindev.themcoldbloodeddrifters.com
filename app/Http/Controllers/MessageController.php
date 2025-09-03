<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessages;


class MessageController extends Controller
{
    public function load_messages(Request $request)
    {
        $id = $request->query('id'); // e.g. /messages/load_messages?id=123

        if ($id) {
            $message = ContactMessages::find($id);

            if (!$message) {
                return response()->json(['error' => 'Message not found'], 404);
            }

            return response()->json($message);
        }

        // otherwise return all
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

    public function get_unread_count()
    {
        $count = ContactMessages::where('read', false)->count();
        return response()->json(['unread_count' => $count]);
    }
    public function delete(Request $request)
    {
        $message = ContactMessages::find($request->id);

        if (!$message) {
            return response()->json(['success' => false, 'error' => 'Message not found']);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }
}
