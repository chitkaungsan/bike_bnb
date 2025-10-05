<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Store;
use App\Models\StoreImage;
class StoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'location'    => 'required|string|max:255',
            'logo'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'front'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'side'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'outside'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'inside'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        // 1️⃣ Save store basic info
        $store = new Store();
        $store->name = $request->name;
        $store->description = $request->description;
        $store->location = $request->location;
        $store->user_id = $request->user_id; // logged-in user

        // Upload logo & cover_photo to S3
        if ($request->hasFile('logo')) {
            $store->logo = $this->uploadToS3($request->file('logo'), 'logo');
        }
        if ($request->hasFile('cover_photo')) {
            $store->cover_photo = $this->uploadToS3($request->file('cover_photo'), 'cover_photo');
        }

        $store->save();

        // 2️⃣ Save multiple store images
        $images = ['front' => true, 'side' => false, 'outside' => false, 'inside' => false];

        foreach ($images as $field => $isMain) {
            if ($request->hasFile($field)) {
                $path = $this->uploadToS3($request->file($field), $field);

                StoreImage::create([
                    'store_id'   => $store->id,
                    'image_path' => $path,
                    'is_main'    => $isMain,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Store created successfully!',
            // 'data'    => $store->load('images'), // load related images
        ]);
    }
    protected function uploadToS3($file, $folder)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName  = 'stores/' . $folder . '/' . Str::random(20) . '.' . $extension;

        Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');

        if (!Storage::disk('s3')->exists($fileName)) {
            throw new \Exception("Failed to upload {$folder} to S3");
        }

        return Storage::disk('s3')->url($fileName);
    }
}
