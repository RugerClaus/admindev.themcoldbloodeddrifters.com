<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CarouselImages;

class CarouselController extends Controller
{
    public function list()
    {
        $items = CarouselImages::orderBy('id')->get([
            'id',
            'src',
            'caption',
            'blurb'
        ]);

        return response()->json($items);
    }

    public function read($id)
    {
        $item = CarouselImages::findOrFail($id);
        return response()->json($item);
    }

    public function create(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
            'caption' => 'nullable|string|max:255',
            'blurb' => 'nullable|string|max:255'
        ]);

        $path = $request->file('image')->store('carousel', 'media');
        $url = Storage::disk('media')->url($path);

        $item = CarouselImages::create([
            'src' => $url,
            'caption' => $request->caption,
            'blurb' => $request->blurb
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:carousel_images,id',
            'image' => 'nullable|image|max:4096',
            'caption' => 'nullable|string|max:255',
            'blurb' => 'nullable|string|max:255'
        ]);

        $item = CarouselImages::findOrFail($request->id);

        $data = [
            'caption' => $request->caption,
            'blurb' => $request->blurb
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $prefix = Storage::disk('media')->url('');
            if (str_starts_with($item->src, $prefix)) {
                $oldRel = str_replace($prefix, '', $item->src);
                if (Storage::disk('media')->exists($oldRel)) {
                    Storage::disk('media')->delete($oldRel);
                }
            }
            $newPath = $request->file('image')->store('carousel', 'media');
            $data['src'] = Storage::disk('media')->url($newPath);
        }

        $item->update($data);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:carousel_images,id'
        ]);

        $item = CarouselImages::findOrFail($request->id);

        $prefix = Storage::disk('media')->url('');
        if (str_starts_with($item->src, $prefix)) {
            $rel = str_replace($prefix, '', $item->src);
            if (Storage::disk('media')->exists($rel)) {
                Storage::disk('media')->delete($rel);
            }
        }

        $item->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted']);
    }
}
