<?php

namespace App\Http\Controllers;

use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        $agendas = Agenda::latest()->take($limit)->get();
        return AgendaResource::collection($agendas);
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
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'implementation' => 'required',
                'time' => 'required',
                'author' => 'required|string|max:255',
                'location' => 'required|string',
                'kordinator' => 'required|string|max:255',
                'thumbnail' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            ], [
                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Kolom deskripsi harus diisi.',
                'description.string' => 'Kolom deskripsi harus berupa string.',

                'implementation.required' => 'Kolom tanggal pelaksanaan harus diisi.',

                'time.required' => 'Kolom waktu harus diisi.',

                'author.required' => 'Kolom author harus diisi.',

                'location.required' => 'Kolom lokasi harus diisi.',
                'location.string' => 'Kolom lokasi harus berupa string.',

                'kordinator.required' => 'Kolom koordinator harus diisi.',
                'kordinator.string' => 'Kolom koordinator harus berupa string.',
                'kordinator.max' => 'Kolom koordinator tidak boleh lebih dari 255 karakter.',

                'thumbnail.required' => 'Kolom thumbnail harus diisi.',
                'thumbnail.mimes' => 'Kolom thumbnail harus berupa gambar.',
                'thumbnail.max' => 'Kolom thumbnail tidak boleh lebih dari 2 MB.',
            ]);

            if ($request->hasFile('thumbnail')) {
                $credentials['thumbnail'] = $request->file('thumbnail')->store('agendas', 'public');
            }

            Agenda::create($credentials);
            return response()->json(['status' => 'success'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors(), 422]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        return new AgendaResource($agenda);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agenda $agenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        try {
            $credentials = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'implementation' => 'required',
                'time' => 'required',
                'location' => 'required|string',
                'kordinator' => 'required|string|max:255',
            ], [
                'title.required' => 'Kolom title harus diisi.',
                'title.string' => 'Kolom title harus berupa string.',
                'title.max' => 'Kolom title tidak boleh lebih dari 255 karakter.',

                'description.required' => 'Kolom deskripsi harus diisi.',
                'description.string' => 'Kolom deskripsi harus berupa string.',

                'implementation.required' => 'Kolom tanggal pelaksanaan harus diisi.',

                'time.required' => 'Kolom waktu harus diisi.',

                'location.required' => 'Kolom lokasi harus diisi.',
                'location.string' => 'Kolom lokasi harus berupa string.',

                'kordinator.required' => 'Kolom koordinator harus diisi.',
                'kordinator.string' => 'Kolom koordinator harus berupa string.',
                'kordinator.max' => 'Kolom koordinator tidak boleh lebih dari 255 karakter.',
            ]);
            if ($request->hasFile('thumbnail')) {
                if (Storage::disk('public')->exists($agenda->thumbnail)) {
                    Storage::disk('public')->delete($agenda->thumbnail);
                }
                $credentials['thumbnail'] = $request->file('thumbnail')->store('agendas', 'public');
            }
            $agenda->update($credentials);
            return response()->json(['status' => 'success'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors(), 422]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        try {
            if (Storage::disk('public')->exists($agenda->thumbnail)) {
                Storage::disk('public')->delete($agenda->thumbnail);
            }
            $agenda->delete();
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
