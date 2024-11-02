<?php

namespace App\Http\Controllers;

use App\Models\VisiMisi;
use Illuminate\Http\Request;

class VisiMisiController extends Controller
{
    public function index()
    {
        $visiMisi = VisiMisi::all()->first();
        return response()->json(['data' => $visiMisi], 200);
    }
    public function create(Request $request)
    {
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'visi_description' => 'required|string',
                'misi_description' => 'required|string',
                // Add other fields and their validation rules
            ]);

            // Create a new VisiMisi record with the validated data
            VisiMisi::create($validatedData);

            return response()->json('success', 201); // 201 for resource created
        } catch (\Exception $e) {
            // Log the error message

            // Return a 500 Internal Server Error response
            return response()->json('An error occurred' . $e->getMessage(), 500);
        }
    }
    public function show($id)
    {
        $visiMisi = VisiMisi::find($id);
        return response()->json($visiMisi, 200);
    }

    public function update($id, Request $request)
    {
        $credentials = $request->validate([
            'visi_description' => 'required|string',
            'misi_description' => 'required|string',
        ], [
            'visi_description.required' => 'Kolom visi harus diisi.',
            'visi_description.string' => 'Kolom visi harus berupa string.',
            'misi_description.required' => 'Kolom misi harus diisi.',
            'misi_description.string' => 'Kolom misi harus berupa string.',
        ]);

        $visiMisi = VisiMisi::find($id);
        $visiMisi->update($credentials);
        return response()->json(['status' => 'success'], 200);
    }
}
