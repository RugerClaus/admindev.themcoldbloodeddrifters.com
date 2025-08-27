<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\BandMembers;

class BandMembersController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'bio_name' => 'required|string|max:255',
            'bio_instrument' => 'required|string|max:512',
            'bio_text' => 'nullable|string',
            'bio_portrait' => 'nullable|image|max:2048',
        ]);

        $member = BandMembers::where('user_id', auth()->id())->firstOrFail();

        $updateData = [
            'name' => $request->bio_name,
            'instrument' => $request->bio_instrument,
            'bio' => $request->bio_text,
        ];

        if ($request->hasFile('bio_portrait') && $request->file('bio_portrait')->isValid()) {
            $portrait_path = $request->file('bio_portrait')->store('band_members', 'media');
            $updateData['portrait'] = Storage::disk('media')->url($portrait_path);
        }
        $member->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Your bio has been updated!',
            'updated_fields' => $updateData,
        ]);
    }
    public function delete_portrait()
    {
        $member = BandMembers::where('user_id', auth()->id())->firstOrFail();

        if ($member->portrait) {
            $path = str_replace(Storage::disk('media')->url(''), '', $member->portrait);
            if (Storage::disk('media')->exists($path)) {
                Storage::disk('media')->delete($path);
            }

            $member->portrait = 'https://placehold.co/300x700';
            $member->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Portrait deleted successfully.',
        ]);
    }

}
