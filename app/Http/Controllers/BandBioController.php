<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;

class BandBioController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'band_list_left_to_right' => 'required|string|max:255',
            'bio' => 'required|min:80',
            'image' => 'nullable|image|max:2048',
            'imgalt' => 'nullable|string|max:255'
        ]);

        $band = Band::firstOrFail();

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Subfolder for this type of media
            $subfolder = 'band/bio';

            // Ensure directory exists
            $fullPath = public_path($subfolder);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Filename can be consistent or include timestamp for uniqueness
            $filename = 'band_photo.' . $image->getClientOriginalExtension();

            // Move uploaded file to subfolder
            $image->move($fullPath, $filename);

            // Store relative URL to DB
            $validated['image_url'] = $subfolder . '/' . $filename;
        }

        // Prepare data for update
        $updateData = [
            'name' => $validated['name'],
            'band_list_left_to_right' => $validated['band_list_left_to_right'],
            'bio' => $validated['bio'],
        ];

        if (isset($validated['image_url'])) {
            $updateData['image_url'] = $validated['image_url'];
        }

        if (!empty($validated['imgalt'])) {
            $updateData['image_alt'] = $validated['imgalt'];
        }

        $band->update($updateData);

        return redirect()->route('band.edit')->with('success', 'Band bio updated!');
    }
}
