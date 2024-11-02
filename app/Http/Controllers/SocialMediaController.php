<?php

namespace App\Http\Controllers;

use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $socialMedia = SocialMedia::all()->first();
        return response()->json(['data' => $socialMedia]);
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
                'instagram' => 'required',
                'facebook' => 'required',
                'youtube' => 'required',
                'twitter' => 'required',
                'linkedind' => 'required',
            ]);
            SocialMedia::create($credentials);
            return response()->json(['status' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialMedia $socialMedia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialMedia $socialMedia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialMedia $socialMedia, $id)
    {
        try {
            $credentials = $request->validate([
                'instagram' => 'required',
                'facebook' => 'required',
                'youtube' => 'required',
                'twitter' => 'required',
                'linkedind' => 'required',
            ]);
            $socialMedia = SocialMedia::find($id);
            $socialMedia->update($credentials);
            return response()->json(['status' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()]);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialMedia $socialMedia)
    {
        try {
            $socialMedia = SocialMedia::find($socialMedia->id);
            SocialMedia::destroy($socialMedia->id);
            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error']);
        }
    }
}
