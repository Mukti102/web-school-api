<?php

namespace App\Http\Controllers;

use App\Models\Gelombang;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gelombang = Gelombang::with('students')->get();
        return response()->json(['data' => $gelombang], 200);
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
                'angkatan' => 'required',
                'gelombang_ke' => 'required',
                'kuota' => 'required'
            ]);
            // Tutup gelombang lain yang saat ini terbuka
            Gelombang::where('status', 'open')->update(['status' => 'close']);
            Gelombang::create($credentials);
            return response()->json(['status' => 'success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gelombang = Gelombang::find($id);
        return response()->json(['data' => $gelombang], 200);
    }

    public function switch_status($id)
    {
        $gelombang = Gelombang::find($id);

        if ($gelombang) {
            if ($gelombang->status === 'close') {
                // Tutup gelombang lain yang saat ini terbuka
                Gelombang::where('status', 'open')->update(['status' => 'close']);
                $gelombang->status = 'open';
            } else {
                $gelombang->status = 'close';
            }

            $gelombang->save();
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['message' => 'Gelombang not found'], 404);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $credentials = $request->validate([
                'angkatan' => 'required',
                'gelombang_ke' => 'required',
                'kuota' => 'required'
            ]);
            $gelombang = Gelombang::find($id);
            $gelombang->update($credentials);
            return response()->json(['status' => 'success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gelombang = Gelombang::find($id);
        Gelombang::destroy(($gelombang->id));
        return response()->json(['status' => 'success']);
    }
}
