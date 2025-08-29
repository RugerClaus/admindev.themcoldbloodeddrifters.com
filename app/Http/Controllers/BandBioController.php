<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\Band;

class BandBioController extends Controller
{

    public function update(Request $request)
    {
        try {
            $request->validate([
                'bio_name' => 'required|string|max:255',
                'bio_list_left_to_right' => 'nullable|string|max:255',
                'bio_text' => 'nullable|string|min:80',
                'bio_image' => 'nullable|image|max:2048',
                'bio_imgalt' => 'nullable|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        }

        $band = Band::firstOrFail();

        $updateData = [
            'name' => $request->bio_name,
            'band_list_left_to_right' => $request->bio_list_left_to_right,
            'bio' => $request->bio_text,
        ];

        if ($request->hasFile('bio_image') && $request->file('bio_image')->isValid()) {
            $image_path = $request->file('bio_image')->store('band/bio', 'media');
            $updateData['image_url'] = Storage::disk('media')->url($image_path);
        }

        if (!empty($request->bio_imgalt)) {
            $updateData['image_alt'] = $request->bio_imgalt;
        }

        $band->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Band bio has been updated!',
            'updated_fields' => $updateData,
        ]);
    }


    public function delete_image()
    {
        $band = Band::firstOrFail();

        if ($band->image_url) {
            $path = str_replace(Storage::disk('media')->url(''), '', $band->image_url);
            if (Storage::disk('media')->exists($path)) {
                Storage::disk('media')->delete($path);
            }

            $band->image_url = 'https://placehold.co/600x400';
            $band->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Band image deleted successfully.',
        ]);
    }
}
