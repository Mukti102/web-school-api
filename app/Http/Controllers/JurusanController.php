<?php

namespace App\Http\Controllers;

use App\Http\Resources\jurusanResource;
use App\Models\Jurusan;
use App\Models\JurusanGalery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        if ($limit) {
            $jurusan = Jurusan::take($limit)->get();
        } else {
            $jurusan = Jurusan::with('jurusanGalery')->get();
        }
        return jurusanResource::collection($jurusan);
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
            // return response()->json($request->file('thumbnail'));
            // Define validation rules and messages
            $validatedData = $request->validate([
                'author' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'galery' => 'array|max:5',
                'thumbnail' => 'file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'author.string' => 'Penulis harus berupa teks.',
                'author.max' => 'Penulis tidak boleh lebih dari 255 karakter.',

                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',

            ]);
            if ($request->file('thumbnail')) {
                $validatedData['thumbnail'] = $request->file('thumbnail')->store('jurusans', 'public');
            }

            // Create a new article with the validated data
            $jurusan = Jurusan::create($validatedData);
            // Return a success response
            if ($request->hasFile('galery')) {
                foreach ($request->file('galery') as $key => $value) {
                    $filePath =  env('ENDPOINT') . 'storage/' . $value->store('jurusans/galery', 'public');
                    if ($filePath) {
                        JurusanGalery::create([
                            'jurusan_id' => $jurusan->id,
                            'photo' => $filePath
                        ]);
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'jurusan created successfully',
                'data' => $jurusan
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan)
    {
        $jurusan = $jurusan->load('jurusanGalery');
        return new jurusanResource($jurusan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan)
    {
        try {
            $credentials = $request->validate([
                'author' => 'required|string',
                'title' => 'required',
                'description' => 'required',
                // 'thumbnail' => 'file|mimes:jpeg,png,jpg|max:2048',
                'galery' => 'array|max:5',
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'author.string' => 'Penulis harus berupa teks.',

                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',

                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',
            ]);
            if ($request->hasFile('thumbnail')) {
                // Delete the old file
                if ($jurusan->thumbnail) {
                    $thumbnailPath = env('ENDPOINT') . 'storage/' . $jurusan->thumbnail;
                    if (Storage::disk('public')->exists($thumbnailPath)) {
                        if (!Storage::disk('public')->delete($thumbnailPath)) {
                            throw new \Exception('Failed to delete old file');
                        }
                    }
                }
                $filePath = $request->file('thumbnail')->store('jurusans', 'public');
                if (!$filePath) {
                    throw new \Exception('Failed to store file');
                }
                $credentials['thumbnail'] = $filePath;
            }
            if ($request->hasFile('galery')) {
                foreach ($request->file('galery') as $key => $value) {
                    $filePath =  env('ENDPOINT') . 'storage/' . $value->store('jurusans/galery', 'public');
                    if ($filePath) {
                        JurusanGalery::create([
                            'jurusan_id' => $jurusan->id,
                            'photo' => $filePath
                        ]);
                    }
                }
            }
            $jurusan->update($credentials);
            return response()->json(['status' => 'success', 'message' => 'jurusan Updated'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan)
    {

        try {
            if ($jurusan->galery) { // Ensure galery is accessed correctly as a relationship
                foreach ($jurusan->galery as $key => $value) { // Loop through each gallery item
                    $galeryPath = $value->photo; // Use the photo path from the $value object
                    $parts = explode('/', $galeryPath); // Split the string by '/'
                    $lastPart = (string) end($parts); // Get the last element of the array (file name)

                    if ($lastPart) { // Check if the file name exists
                        Storage::disk('public')->delete('jurusans/galery/' . $lastPart); // Delete the file from the 'public' disk
                    }
                }
            }
            if ($jurusan->thumbnail) {
                $thumbnailPath = $jurusan->thumbnail;
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    if (!Storage::disk('public')->delete($thumbnailPath)) {
                        throw new \Exception('Failed to delete old file');
                    }
                }
            }
            $jurusan->delete();
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'deleted is fail because ' . $th->getMessage()], 500);
        }
    }

    public function deleteGalery($id)
    {
        $galery = JurusanGalery::find($id);
        $galeryPath = $galery->photo;
        $parts = explode('/', $galeryPath);
        $lastPart = (string) end($parts);
        if ($lastPart) {
            Storage::disk('public')->delete('jurusan/galery/' . $lastPart);
        }
        $galery->delete();
        return response()->json(['status' => 'success', 'message' => 'jurusan galery Deleted'], 200);
    }
}
