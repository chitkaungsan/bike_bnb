<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Bike;
use App\Models\BikeImage;
class BikeController extends Controller
{
  public function bikeStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'brand_id' => 'required|integer',
            'cat_id' => 'required|integer',
            'photo' => 'nullable|image|max:4096',
            'license_image' => 'nullable|image|max:4096',
            'files.*' => 'nullable|image|max:4096',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'type' => 'nullable|string|max:255',
            'bike_no' => 'nullable|string|max:255',
        ]);

        // --- Upload main photo ---
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = Str::random(10) . '.' . $photo->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs('bike_images', $photo, $photoName, 'public');
            $validated['photo'] = Storage::disk('s3')->url('bike_images/' . $photoName);
        }

        // --- Upload license image ---
        if ($request->hasFile('license_image')) {
            $license = $request->file('license_image');
            $licenseName = Str::random(10) . '.' . $license->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs('bike_images', $license, $licenseName, 'public');
            $validated['license_image'] = Storage::disk('s3')->url('bike_images/' . $licenseName);
        }

        // --- Create bike record ---user
        $bike = Bike::create(array_merge($validated, [
            'user_id' => $request->user_id, // if using auth
        ]));

        // --- Upload multiple additional bike images ---
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                Storage::disk('s3')->putFileAs('bike_images', $file, $fileName, 'public');
                BikeImage::create([
                    'bike_id' => $bike->id,
                    'photo' => Storage::disk('s3')->url('bike_images/' . $fileName),
                ]);
            }
        }

        return response()->json([
            'message' => 'Bike added successfully!',
        ]);
    }
}
