<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnnouncmentResource;
use App\Models\Announcment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        if ($limit === 'all') {
            $announcments = Announcment::all();
        } else if ($limit) {
            $announcments = Announcment::take($limit)->get();
        } else {
            $announcments = Announcment::paginate(5);
        }
        return AnnouncmentResource::collection($announcments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $credentials = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'author' => 'required|string|max:255',
                'date' => 'required',
                'time' => 'required',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:2048',
                'lampiran' => 'nullable|file|max:2048',
            ], [
                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Kolom deskripsi harus diisi.',
                'description.string' => 'Kolom deskripsi harus berupa string.',

                'author.required' => 'Kolom author harus diisi.',

                'date.required' => 'Kolom date harus diisi.',

                'time.required' => 'Kolom time harus diisi.',

                'thumbnail.required' => 'Kolom thumbnail harus diisi.',

                'thumbnail.file' => 'Kolom thumbnail harus berupa file.',
                'thumbnail.mimes' => 'Kolom thumbnail harus berupa jpeg,png,jpg.',

                'lampiran.file' => 'Kolom lampiran harus berupa file.',
                'lampiran.max' => 'Kolom lampiran tidak boleh lebih dari 2048.',
            ]);
            if ($request->hasFile('thumbnail')) {
                $credentials['thumbnail'] = $request->file('thumbnail')->store('announcments', 'public');
            }
            if ($request->hasFile('lampiran')) {
                $credentials['lampiran'] = $request->file('lampiran')->store('announcments/lampiran', 'public');
            }
            $announcment = Announcment::create($credentials);
            return response()->json(['status' => 'success', 'message' => 'Announcment created successfully', 'data' => $announcment, 'status' => 'success'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcment $announcment)
    {
        return new AnnouncmentResource($announcment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcment $announcment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcment $announcment)
    {
        try {
            $credentials = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required',
                'time' => 'required',
                // 'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',
            ]);
            if ($request->hasFile('thumbnail')) {
                if (Storage::disk('public')->exists($announcment->thumbnail)) {
                    Storage::disk('public')->delete($announcment->thumbnail);
                }
                $credentials['thumbnail'] = $request->file('thumbnail')->store('announcments', 'public');
            }
            if ($request->hasFile('lampiran')) {
                if (Storage::disk('public')->exists($announcment->lampiran)) {
                    Storage::disk('public')->delete($announcment->lampiran);
                }
                $credentials['lampiran'] = $request->file('lampiran')->store('announcments/lampiran', 'public');
            }
            $announcment->update($credentials);
            return response()->json(['status' => 'success', 'message' => 'Announcment updated successfully', 'data' => $announcment], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcment $announcment)
    {
        if ($announcment->thumbnail && Storage::disk('public')->exists($announcment->thumbnail)) {
            Storage::disk('public')->delete($announcment->thumbnail);
        }

        if ($announcment->lampiran && Storage::disk('public')->exists($announcment->lampiran)) {
            Storage::disk('public')->delete($announcment->lampiran);
        }

        Announcment::destroy($announcment->id);

        return response()->json(['status' => 'success', 'message' => 'deleted is successful'], 200);
    }
}
