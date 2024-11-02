<?php

namespace App\Http\Controllers;

use App\Models\PendaftaransRule;
use Illuminate\Http\Request;

class PendaftaransRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => PendaftaransRule::all()]);
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
                'title' => 'required',
                'description' => 'required'
            ], [
                'title.required' => 'Judul Harus Di Isi',
                'description.required' => 'Deskripsi Harus Di Isi',
            ]);

            PendaftaransRule::create($credentials);
            return response()->json(['status' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors(), 422]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PendaftaransRule $pendaftaransRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PendaftaransRule $pendaftaransRule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PendaftaransRule $pendaftaransRule)
    {
        try {
            $credentials = $request->validate([
                'title' => 'required',
                'description' => 'required'
            ], [
                'title.required' => 'Judul Harus Di Isi',
                'description.required' => 'Deskripsi Harus Di Isi',
            ]);

            $pendaftaransRule->update($credentials);
            return response()->json(['status' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors(), 422]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PendaftaransRule $pendaftaransRule, $id)
    {
        try {
            $pendaftaransRule = PendaftaransRule::find($id);
            PendaftaransRule::destroy($pendaftaransRule->id);
            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
