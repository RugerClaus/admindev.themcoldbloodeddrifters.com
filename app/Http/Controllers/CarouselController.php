<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CarouselImages;

class CarouselController extends Controller
{
    public function list()
    {
        $items = CarouselImages::orderBy('sort_order')->get([
            'id','url','alt','caption','sort_order'
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
            'alt' => 'required|string|max:255',
            'caption' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1'
        ]);

        $path = $request->file('image')->store('carousel', 'media');
        $url = Storage::disk('media')->url($path);

        $item = CarouselImages::create([
            'url' => $url,
            'alt' => $request->alt,
            'caption' => $request->caption,
            'sort_order' => $request->sort_order ?? (CarouselImages::max('sort_order') + 1)
        ]);

        return response()->json(['success'=>true, 'item'=>$item]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:carousel_images,id',
            'image' => 'nullable|image|max:4096',
            'alt' => 'required|string|max:255',
            'caption' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:1'
        ]);

        $item = CarouselImages::findOrFail($request->id);

        $data = [
            'alt' => $request->alt,
            'caption' => $request->caption,
        ];
        if ($request->filled('sort_order')) {
            $data['sort_order'] = $request->sort_order;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $prefix = Storage::disk('media')->url('');
            if (str_starts_with($item->url, $prefix)) {
                $oldRel = str_replace($prefix, '', $item->url);
                if (Storage::disk('media')->exists($oldRel)) {
                    Storage::disk('media')->delete($oldRel);
                }
            }
            $newPath = $request->file('image')->store('carousel', 'media');
            $data['url'] = Storage::disk('media')->url($newPath);
        }

        $item->update($data);

        return response()->json(['success'=>true, 'item'=>$item]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:carousel_images,id',
        ]);

        $item = CarouselImages::findOrFail($request->id);

        // try to delete file (if in our media disk)
        $prefix = Storage::disk('media')->url('');
        if (str_starts_with($item->url, $prefix)) {
            $rel = str_replace($prefix, '', $item->url);
            if (Storage::disk('media')->exists($rel)) {
                Storage::disk('media')->delete($rel);
            }
        }

        $item->delete();

        return response()->json(['success'=>true, 'message'=>'Image deleted']);
    }
}
