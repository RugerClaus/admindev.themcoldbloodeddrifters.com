<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeData;

class HomeController extends Controller
{
    public function update_left(Request $request)
    {
        $request->validate([
            'left' => 'required|string|max:5000',
        ]);

        try {
            $homeData = HomeData::firstOrCreate([]);
            $homeData->left = $request->input('left');
            $homeData->save();

            return response()->json([
                'success' => true,
                'message' => 'Left/Top text updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update text.',
            ], 500);
        }
    }
    public function update_right(Request $request)
    {
        $request->validate([
            'right' => 'required|string|max:5000',
        ]);

        try {
            $homeData = HomeData::firstOrCreate([]);
            $homeData->right = $request->input('right');
            $homeData->save();

            return response()->json([
                'success' => true,
                'message' => 'Right/Bottom text updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update text.',
            ], 500);
        }
    }
}
