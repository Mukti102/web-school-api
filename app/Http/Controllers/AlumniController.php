<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlumniResource;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AlumniResource::collection(Alumni::all());
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
                'name' => 'required|string',
                'photo' => 'required',
                'angkatan' => "required",
                'response' => 'required|max:50',
                'comment' => 'required',
            ]);

            if ($request->hasFile('photo')) {
                $credentials['photo'] = $request->file('photo')->store('alumni', 'public');
            }

            Alumni::create($credentials);
            return response()->json([
                'status' => 'success',
                'message' => 'Alumni created successfully',
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
    public function show(Alumni $alumni)
    {
        return new AlumniResource($alumni);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumni $alumni)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alumni $alumni, $id)
    {
        $alumni = Alumni::find($id);
        try {
            $credentials = $request->validate([
                'name' => 'required|string',
                'photo' => 'required',
                'response' => 'required|max:50',
                'comment' => 'required',
            ]);

            if ($request->hasFile('photo')) {
                if (Storage::disk('public')->exists($alumni->photo)) {
                    Storage::disk('public')->delete($alumni->photo);
                }
                $credentials['photo'] = $request->file('photo')->store('alumni', 'public');
            }

            $alumni->update($credentials);

            return response()->json([
                'status' => 'success',
                'message' => 'Alumni updated successfully',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumni $alumni)
    {
        try {
            Alumni::destroy($alumni->id);
            return response()->json(['status' => 'success', 'message' => 'deleted is successfull'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'deleted is fail because ' . $th->getMessage()], 500);
        }
    }
}
