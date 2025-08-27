<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Band;

class BandBioController extends Controller
{
    public function update(Request $requst)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $band = [
                'name' => $band->name,
                'band_list_left_to_right' => $band->band_list_left_to_right,
                'bio' => $band->bio,
                'image' => $band->image_url,
                'imgalt' => $band->image_alt,
            ];
    }
}
