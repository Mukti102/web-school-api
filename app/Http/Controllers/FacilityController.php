<?php

namespace App\Http\Controllers;

use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use App\Models\FacilityGalery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $facilities = Facility::with('galery')->get();
        return FacilityResource::collection($facilities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $credentials = $request->validate([
                'author' => 'required|string',
                'title' => 'required',
                'galery' => 'array',
                'description' => 'required',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($request->hasFile('thumbnail')) {
                $filePath = $request->file('thumbnail')->store('facilities', 'public');
                if (!$filePath) {
                    throw new \Exception('Failed to store file');
                }
                $credentials['thumbnail'] = $filePath;
            }
            $fasilitas = Facility::create($credentials);
            if ($request->hasFile('galery')) {
                foreach ($request->file('galery') as $key => $value) {
                    $filePath =  env('ENDPOINT') . 'storage/' . $value->store('facilities/galery', 'public');
                    if ($filePath) {
                        FacilityGalery::create([
                            'facility_id' => $fasilitas->id,
                            'photo' => $filePath
                        ]);
                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'Created success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json('error created' . $th->getMessage(), 500);
        }
    }


    public function show(Facility $facility)
    {
        $facility = Facility::where('id', $facility->id)->with('galery')->get()->first();
        return new FacilityResource($facility);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facility $facility)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facility $facility)
    {
        try {
            $credentials = $request->validate([
                'author' => 'required|string',
                'title' => 'required',
                'description' => 'required',
                // 'thumbnail' => 'mimes:jpeg,png,jpg|max:2048',
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'author.string' => 'Penulis harus berupa teks.',
                'title.required' => 'Judul wajib diisi.',
                'description.required' => 'Deskripsi wajib diisi.',
                'thumbnail.mimes' => 'File harus berekstensi jpeg, png, jpg.',
            ]);

            if ($request->hasFile('thumbnail')) {
                // Delete the old file
                if ($facility->thumbnail) {
                    $thumbnailPath = $facility->thumbnail;
                    if (Storage::disk('public')->exists($thumbnailPath)) {
                        if (!Storage::disk('public')->delete($thumbnailPath)) {
                            throw new \Exception('Failed to delete old file');
                        }
                    }
                }
                $filePath = $request->file('thumbnail')->store('facilities', 'public');
                if (!$filePath) {
                    throw new \Exception('Failed to store file');
                }
                $credentials['thumbnail'] = $filePath;
            }
            if ($request->hasFile('galery')) {
                foreach ($request->file('galery') as $key => $value) {
                    $filePath =  env('ENDPOINT') . 'storage/' . $value->store('facilities/galery', 'public');
                    if ($filePath) {
                        FacilityGalery::create([
                            'facility_id' => $facility->id,
                            'photo' => $filePath
                        ]);
                    }
                }
            }
            $facility->update($credentials);
            return response()->json(['status' => 'success', 'message' => 'Facility Updated'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return response()->json('error created ' . $error, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        try {
            if ($facility->galery->count() > 0) { // Ensure galery is accessed correctly as a relationship
                foreach ($facility->galery as $key => $value) { // Loop through each gallery item
                    $galeryPath = $value->photo; // Use the photo path from the $value object
                    $parts = explode('/', $galeryPath); // Split the string by '/'
                    $lastPart = (string) end($parts); // Get the last element of the array (file name)
                    if ($lastPart) { // Check if the file name exists
                        Storage::disk('public')->delete('facilities/galery/' . $lastPart); // Delete the file from the 'public' disk
                    }
                }
            }

            if ($facility->thumbnail) {
                $thumbnailPath = $facility->thumbnail;
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    if (!Storage::disk('public')->delete($thumbnailPath)) {
                        throw new \Exception('Failed to delete old file');
                    }
                }
            }

            Facility::destroy($facility->id);
            return response()->json(['status' => 'success', 'message' => 'Facility Deleted'], 200);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return response()->json('error deleted because ' . $error, 500);
        }
    }

    public function deleteGalery($id)
    {
        $galery = FacilityGalery::find($id);
        $galeryPath = $galery->photo;
        $parts = explode('/', $galeryPath);
        $lastPart = (string) end($parts);
        if ($lastPart) {
            Storage::disk('public')->delete('facilities/galery/' . $lastPart);
        }
        $galery->delete();
        return response()->json(['status' => 'success', 'message' => 'Facility Deleted'], 200);
    }
}
