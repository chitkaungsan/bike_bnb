<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Brand;
use App\Models\Bike;
use App\Models\BikeImage;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
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
            'description' => 'nullable|string',
            'store_id' => 'required|integer',
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
            'store_id' => $request->store_id,
        ]));

        // --- Upload multiple additional bike images ---
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
                Storage::disk('s3')->putFileAs('bike_images', $file, $fileName, 'public');
                BikeImage::create([
                    'bike_id' => $bike->id,
                    'user_id' => $request->user_id,
                    'store_id' => $request->store_id,
                    'photo' => Storage::disk('s3')->url('bike_images/' . $fileName),
                ]);
            }
        }

        return response()->json([
            'message' => 'Bike added successfully!',
        ]);
    }
     public function getBrands()
    {
        $brands = Brand::select('id', 'name', 'logo')->get();
        return response()->json($brands);
    }
    public function getCategories()
    {
        $categories = Category::select('id', 'name')->get();
        return response()->json($categories);
    }
    public function getBikeList(Request $request, $user_id)
    {
        $bikes = DB::table('bikes')
            ->join('stores', 'bikes.store_id', '=', 'stores.id')
            ->select(
                'bikes.*',
                'stores.name as store_name',
                'stores.address as store_address',
                'stores.id as store_id',
                'stores.logo as store_logo',
            )
            ->where('bikes.user_id', $user_id)
            ->get();
        return response()->json($bikes);
    }
    public function getBikes(Request $request)
    {
        
        $bikes = DB::table('bikes')
            ->join('stores', 'bikes.store_id', '=', 'stores.id')
            ->select(
                'bikes.*',
                'stores.name as store_name',
                'stores.address as store_address',
                'stores.id as store_id',
                'stores.logo as store_logo',
            )
            ->paginate($request->per_page ?? 10);
        return response()->json($bikes);
    }
    public function getBikeWithId($id)
{
    $bike = DB::table('bikes')
        ->join('stores', 'bikes.store_id', '=', 'stores.id')
        ->where('bikes.id', $id)
        ->select(
            'bikes.*',
            'stores.name as store_name',
            'stores.address as store_address',
            'stores.id as store_id',
            'stores.logo as store_logo'
        )
        ->first(); // get single bike

    if (!$bike) {
        return response()->json(['message' => 'Bike not found'], 404);
    }

    // get all images separately
    $images = DB::table('bike_images')
        ->where('bike_id', $id)
        ->pluck('photo'); // or 'image_path' depending on your column

    // attach images as array
    $bike->images = $images;

    return response()->json($bike);
}
}
