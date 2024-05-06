<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $uploadedPhotos = [];
        $skippedPhotos = [];

        if ($request->hasfile('photos')) {
            foreach ($request->file('photos') as $file) {
                $existingPhoto = Photo::where('alias', $file->getClientOriginalName())->first();

                if ($existingPhoto) {
                    $skippedPhotos[] = $file->getClientOriginalName();
                    continue;
                }

                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/products', $imageName);
                $url = Storage::url($path);

                $photo = Photo::create([
                    'name' => $imageName,
                    'alias' => $file->getClientOriginalName(),
                    'path' => $url,
                    'uploaded_by' => Auth::id(),
                ]);

                $uploadedPhotos[] = $photo;
            }
        }

        return response()->json([
            'message' => 'Proces nahrávání dokončen.',
            'uploaded' => $uploadedPhotos,
            'skipped' => $skippedPhotos,
        ]);
    }



    public function assignToProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:photos,id'
        ]);

        $product = Product::find($request->product_id);
        $product->photos()->sync($request->photo_ids);

        return response()->json(['message' => 'Fotografie byly přiřazeny k produktu.']);
    }

    
    public function list(Request $request)
    {
        $aliasFilter = $request->input('alias', '');
        $productId = $request->input('productId', null);

        $photos = Photo::where('alias', 'like', '%' . $aliasFilter . '%')
            ->get()
            ->map(function ($photo) use ($productId) {
                $selected = false;
                if ($productId) {
                    $selected = $photo->products()->where('product_id', $productId)->exists();
                }

                return [
                    'id' => $photo->id,
                    'name' => $photo->name,
                    'url' => url($photo->path),
                    'alias' => $photo->alias,
                    'selected' => $selected
                ];
            });

        return response()->json(['photos' => $photos]);
    }




    public function updateAlias(Request $request, $photoId)
    {
        $request->validate([
            'alias' => 'required|string|max:255',
        ]);

        $photo = Photo::findOrFail($photoId);
        $photo->alias = $request->alias;
        $photo->save();

        return response()->json(['message' => 'Alias byl úspěšně změněn.']);
    }

    public function deletePhotos(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'exists:photos,id',
        ]);

        $deletedPhotoIds = [];
        $dependentProducts = [];
        foreach ($request->photos as $photoId) {
            $photo = Photo::with('products')->find($photoId);

            if ($photo->products->count() > 0) {
                $dependentProducts[] = $photo->alias;
                continue;
            }

            $currentImagePath = str_replace('storage/', 'public/', $photo->path);
            Storage::delete($currentImagePath);
            $photo->delete();
            $deletedPhotoIds[] = $photoId;
        }

        if (count($dependentProducts) > 0) {
            return response()->json([
                'message' => 'Některé fotky nelze smazat, protože jsou vázané na produkty.',
                'deletedPhotos' => $deletedPhotoIds,
                'dependentProducts' => $dependentProducts,
            ], 400);
        }

        return response()->json([
            'message' => 'Vybrané fotky byly smazány.',
            'deletedPhotos' => $deletedPhotoIds,
        ]);
    }


}
