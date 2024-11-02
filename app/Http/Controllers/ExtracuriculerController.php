<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExtracuriculerResource;
use App\Models\Extracuriculer;
use App\Models\ExtraCuriculerGalery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExtracuriculerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $extracuriculer = Extracuriculer::with('galery')->get();
        return ExtracuriculerResource::collection(($extracuriculer));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $credentials = $request->validate([
                'author' => 'required|string',
                'title' => 'required',
                'description' => 'required',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'title.required' => 'Judul wajib diisi.',
                'description.required' => 'Deskripsi wajib diisi.',
                'thumbnail.required' => 'Thumbnail wajib diisi.',
                'thumbnail.mimes' => 'File harus berekstensi jpeg, png, jpg.',
                'thumbnail.max' => 'File tidak boleh lebih dari 2 MB.',
                'thumbnail.file' => 'File harus berupa gambar.',
            ]);

            if ($request->hasFile('thumbnail')) {
                // Debugging: Check if the file is valid
                if (!$request->file('thumbnail')->isValid()) {
                    throw new \Exception('Invalid file upload');
                }

                // Store the file and get the path
                $filePath = $request->file('thumbnail')->store('extracuriculers', 'public');
                if (!$filePath) {
                    throw new \Exception('Failed to store file');
                }

                $credentials['thumbnail'] = $filePath;
            }
            $extrakurikuler = Extracuriculer::create($credentials);
            if ($request->hasFile('galery')) {
                foreach ($request->file('galery') as $key => $value) {
                    $filePath =  env('ENDPOINT') . 'storage/' . $value->store('extracuriculers/galery', 'public');
                    if ($filePath) {
                        ExtraCuriculerGalery::create([
                            'extracuriculer_id' => $extrakurikuler->id,
                            'photo' => $filePath
                        ]);
                    }
                }
            }
            return response()->json(['status' => 'success', 'message' => 'success save ', 201]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error occurred: ' . $th->getMessage()], 500);
        }
    }


    public function StorePhoto(Request $request)
    {
        $credentials = $request->validate([
            'photo' => 'required',
        ]);
        Extracuriculer::create($credentials);
        return response()->json('success', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Extracuriculer $extracuriculer, $id)
    {
        $extracuriculer = Extracuriculer::with('galery')->findOrFail($id);
        return new ExtracuriculerResource($extracuriculer);
        // $extracuriculer = Extracuriculer::findOrFail($id);
        // return new ExtracuriculerResource($extracuriculer);
    }
    public function edit(Extracuriculer $extracuriculer)
    {
        //
    }


    public function deleteGalery($id)
    {
        $galery = ExtraCuriculerGalery::find($id);
        $galeryPath = $galery->photo;
        $parts = explode('/', $galeryPath);
        $lastPart = (string) end($parts);
        if ($lastPart) {
            Storage::disk('public')->delete('extracuriculers/galery/' . $lastPart);
        }
        $galery->delete();
        return response()->json(['status' => 'success', 'message' => 'extracuriculers Deleted'], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $extracuriculer = Extracuriculer::findOrFail($id);

            // Validate request data
            $credentials = $request->validate([
                'author' => 'string',
                'title' => 'string',
                'description' => 'string',
                // 'thumbnail' => 'file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'title.required' => 'Judul wajib diisi.',
                'description.required' => 'Deskripsi wajib diisi.',
                'thumbnail.required' => 'Thumbnail wajib diisi.',
                'thumbnail.mimes' => 'File harus berekstensi jpeg, png, jpg.',
                'thumbnail.max' => 'File tidak boleh lebih dari 2 MB.',
                'thumbnail.file' => 'File harus berupa gambar.',
            ]);

            // Check if a new file has been uploaded
            if ($request->hasFile('thumbnail')) {

                // Debugging: Check if the file is valid
                if (!$request->file('thumbnail')->isValid()) {
                    throw new \Exception('Invalid file upload');
                }

                // Delete the old file
                if ($extracuriculer->thumbnail) {
                    $thumbnailPath = $extracuriculer->thumbnail;
                    if (Storage::disk('public')->exists($thumbnailPath)) {
                        if (!Storage::disk('public')->delete($thumbnailPath)) {
                            throw new \Exception('Failed to delete old file');
                        }
                    }
                }

                // Store the new file and get the path
                $filePath = $request->file('thumbnail')->store('extracuriculers', 'public');
                if (!$filePath) {
                    throw new \Exception('Failed to store new file');
                }

                $credentials['thumbnail'] = $filePath;
            }

            if ($request->hasFile('galery')) {
                foreach ($request->file('galery') as $key => $value) {
                    $filePath = $value->store('extracuriculers/galery', 'public');
                    ExtraCuriculerGalery::create(['photo' => env('ENDPOINT') . 'storage/' . $filePath, 'extracuriculer_id' => $extracuriculer->id]);
                }
            }

            // Update the model with new data
            $extracuriculer->update($credentials);

            return response()->json(['status' => 'success', 'message' => 'Update successful', 200]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors(), 422]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error occurred: ' . $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $extracuriculer = Extracuriculer::findOrFail($id);

            if ($extracuriculer->galery->count() > 0) { // Ensure galery is accessed correctly as a relationship
                foreach ($extracuriculer->galery as $key => $value) { // Loop through each gallery item
                    $galeryPath = $value->photo; // Use the photo path from the $value object
                    $parts = explode('/', $galeryPath); // Split the string by '/'
                    $lastPart = (string) end($parts); // Get the last element of the array (file name)

                    if ($lastPart) { // Check if the file name exists
                        Storage::disk('public')->delete('extacuriculers/galery/' . $lastPart); // Delete the file from the 'public' disk
                    }
                }
            }
            // Delete the thumbnail if it exists
            if ($extracuriculer->thumbnail) {
                $thumbnailPath = $extracuriculer->thumbnail;
                // Use the correct storage disk
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    if (!Storage::disk('public')->delete($thumbnailPath)) {
                        throw new \Exception('Failed to delete file');
                    }
                }
            }


            // Delete the Extracuriculer record
            $extracuriculer->delete();

            return response()->json(['status' => 'success', 'message' => 'Success to delete', 200]);
        } catch (\Throwable $th) {
            return response()->json('Delete is failed because ' . $th->getMessage(), 500);
        }
    }
}
