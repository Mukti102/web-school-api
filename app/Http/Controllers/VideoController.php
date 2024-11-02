<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        if ($limit === 'all') {
            $video = Video::latest()->get();
        } else if ($limit) {
            $video = Video::latest()->take($limit)->get();
        } else {
            $video = Video::latest()->paginate(5);
        }
        return  VideoResource::collection($video);
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
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'video_path' => 'required',
                'author' => 'required|string',

                'thumbnail' => 'mimes:jpeg,png,jpg|max:2048',
            ], [
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',

                'video_path.required' => 'Video wajib diisi.',

                'author.required' => 'Penulis wajib diisi.',

                'thumbnail.mimes' => 'File harus berupa jpeg, png, jpg.',
            ]);

            if ($request->file('thumbnail')) {
                $validatedData['thumbnail'] = $request->file('thumbnail')->store('video', 'public');
            }
            // create
            $video = Video::create($validatedData);
            return response()->json(['status' => 'success', 'message' => 'Video created successfully', 'data' => $video], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return new VideoResource($video);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'video_path' => 'required',
            ], [
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',

                'video_path.required' => 'Video wajib diisi.',

            ]);

            if ($request->hasFile('thumbnail')) {
                if (Storage::disk('public')->exists($video->thumbnail)) {
                    Storage::disk('public')->delete($video->thumbnail);
                }
                $validatedData['thumbnail'] = $request->file('thumbnail')->store('video', 'public');
            }
            // update
            $video->update($validatedData);
            return response()->json(['status' => 'success', 'message' => 'Video updated successfully', 'data' => $video], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
        $video->update($validatedData);
        return response()->json(['status' => 'success', 'message' => 'Video updated successfully', 'data' => $video], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        if (Storage::disk('public')->exists($video->thumbnail)) {
            Storage::disk('public')->delete($video->thumbnail);
        }
        $video->delete();
        return response()->json(['status' => 'success', 'message' => 'Video deleted successfully'], 200);
    }
}
