<?php

namespace App\Http\Controllers;

use App\Http\Resources\HeroResource;
use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{

    public function index()
    {
        $hero = Hero::all();
        return HeroResource::collection($hero);
    }

    public function store(Request $request)
    {
        try {
            $credentials = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'image.required' => 'Kolom image harus diisi.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'File harus berupa jpeg,png,jpg,gif,svg.',
            ]);
            if ($request->hasFile('image')) {
                $credentials['image'] = $request->file('image')->store('hero', 'public');
            }
            $hero = Hero::create($credentials);
            return response()->json(['status' => 'success', 'data' => $hero], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $hero = Hero::find($id);
        if (Storage::disk('public')->exists($hero->image)) {
            Storage::disk('public')->delete($hero->image);
        }
        $hero->delete();
        return response()->json(['status' => 'success', 'message' => 'Hero Deleted'], 200);
    }
}
