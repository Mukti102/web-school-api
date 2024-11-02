<?php

namespace App\Http\Controllers;

use App\Http\Resources\PrestationResource;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');

        if ($limit === "all") {
            // Retrieve all prestations, ordered by the latest entries
            $prestations = Prestation::latest()->get();
        } elseif ($limit) {
            // Retrieve a limited number of prestations
            $prestations = Prestation::latest()->take($limit)->get();
        } else {
            // Default to paginated results
            $prestations = Prestation::latest()->paginate(5);
        }

        // Return as a resource collection
        return PrestationResource::collection($prestations);
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
                'status_winner' => 'required',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required',
                'juara' => 'required',
                'time' => 'required',
                'penyelenggara' => 'required',
                'author' => 'required|string|max:255',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'status_winner.required' => 'Kolom status_winner harus diisi.',

                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Kolom deskripsi harus diisi.',
                'description.string' => 'Kolom deskripsi harus berupa string.',

                'date.required' => 'Kolom Tanggal harus diisi.',

                'time.required' => 'Kolom Waktu harus diisi.',

                'juara.required' => 'Kolom juara harus diisi.',

                'author.required' => 'Kolom author harus diisi.',

            ]);

            if ($request->hasFile('thumbnail')) {
                $credentials['thumbnail'] = $request->file('thumbnail')->store('prestasi', 'public');
            }
            $prestation = Prestation::create($credentials);
            return response()->json(['status' => 'success', 'message' => 'Prestation created successfully', 'data' => $prestation], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestation $prestation, $id)
    {
        $prestation = Prestation::find($id);
        return new PrestationResource($prestation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestation $prestation)
    {
        //
    }

    public function updated(Request $request, $id)
    {
        $prestation = Prestation::find($id);
        try {
            $credentials = $request->validate([
                'status_winner' => 'required',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'status_winner.required' => 'Kolom status_winner harus diisi.',
                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Kolom deskripsi harus diisi.',
                'description.string' => 'Kolom deskripsi harus berupa string.',
                'date.required' => 'Kolom Tanggal harus diisi.',
                'time.required' => 'Kolom Waktu harus diisi.',
                'thumbnail.max' => 'Kolom thumbnail tidak boleh lebih dari 2048 karakter.',
            ]);

            if ($request->hasFile('thumbnail')) {

                $this->deleteOldThumbnail($prestation);

                $credentials['thumbnail'] = $request->file('thumbnail')->store('prestasi', 'public');
            }

            $prestation->update($credentials);

            return response()->json(['status' => 'success', 'message' => 'Prestation updated successfully', 'data' => $prestation], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestation $prestation)
    {
        try {
            // Validate request input
            $validatedData = $request->validate([
                'status_winner' => 'required',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'status_winner.required' => 'Kolom status_winner harus diisi.',
                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Kolom deskripsi harus diisi.',
                'description.string' => 'Kolom deskripsi harus berupa string.',
                'date.required' => 'Kolom Tanggal harus diisi.',
                'time.required' => 'Kolom Waktu harus diisi.',
            ]);

            // Check if a new thumbnail file is uploaded
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if it exists
                $this->deleteOldThumbnail($prestation);
                // Store the new thumbnail and add its path to validated data
                $validatedData['thumbnail'] = $request->file('thumbnail')->store('prestasi', 'public');
            }

            // Update the prestation record
            $prestation->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Prestation updated successfully',
                'data' => $prestation,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating prestation.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function deleteOldThumbnail(Prestation $prestation)
    {
        if ($prestation->thumbnail && Storage::disk('public')->exists($prestation->thumbnail)) {
            Storage::disk('public')->delete($prestation->thumbnail);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prestation = Prestation::find($id);
        try {
            if ($prestation->thumbnail) {
                if (Storage::disk('public')->exists($prestation->thumbnail)) {
                    Storage::disk('public')->delete($prestation->thumbnail);
                }
            }
            $prestation->delete();
            return response()->json(['status' => 'success', ',message' => 'Prestation deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
