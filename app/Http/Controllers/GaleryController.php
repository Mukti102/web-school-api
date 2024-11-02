<?php

namespace App\Http\Controllers;

use App\Models\Galery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galery = Galery::all();
        return response()->json(['data' => $galery], 200);
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
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('storage/galery');
            $image->move($destinationPath, $image_name);
            $data = [
                'photo' => env('ENDPOINT') . 'storage/galery/' . (string) $image_name,
            ];
            $galery = Galery::create($data);
            return response()->json(['data' => $galery, 'status' => 'success'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Galery $galery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Galery $galery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galery $galery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galery $galery)
    {
        try {
            if ($galery->photo) {
                $path = explode('/', $galery->photo);
                $last = (string) end($path);
                if ($last) {
                    Storage::disk('public')->delete('galery/' . $last);
                }
            }
            // Proses penghapusan tetap berjalan meskipun photo tidak ada atau gagal dihapus
            $galery->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Deleted Successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
}
