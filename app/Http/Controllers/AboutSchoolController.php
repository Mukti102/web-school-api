<?php

namespace App\Http\Controllers;

use App\Models\AboutSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSchoolController extends Controller
{
    public function index()
    {
        $aboutSchool = AboutSchool::first();
        return response()->json([
            'data' => [
                'tentang_sekolah' => $aboutSchool->tentang_sekolah,
                'visi' => $aboutSchool->visi,
                'misi' => $aboutSchool->misi,
                'thumbnail' => env('ENDPOINT') . "storage/" . $aboutSchool->thumbnail
            ],
            'status' => 'success'
        ]);
    }

    public function update($id)
    {
        try {
            $credentials = request()->validate([
                'tentang_sekolah' => 'required',
                'visi' => 'required',
                'misi' => 'required',
                'thumbnail' => 'required|max:2548',
            ], [
                'tentang_sekolah.required' => 'Tentang sekolah harus diisi',
                'visi.required' => 'Visi harus diisi',
                'misi.required' => 'Misi harus diisi',
                'thumbnail.required' => 'Thumbnail harus diisi',
                'thumbnail.max' => 'Thumbnail maksimal 2 MB',
            ]);

            $aboutSchool = AboutSchool::find($id);
            $aboutSchool->tentang_sekolah = $credentials['tentang_sekolah'];
            $aboutSchool->visi = $credentials['visi'];
            $aboutSchool->misi = $credentials['misi'];
            if (request()->hasFile('thumbnail')) {
                if (Storage::exists($aboutSchool->thumbnail)) {
                    Storage::delete($aboutSchool->thumbnail);
                }
                $pathThubnail = request()->file('thumbnail')->store('about-school', 'public');
                $aboutSchool->thumbnail = $pathThubnail;
            }
            $aboutSchool->save();
            return response()->json([
                'data' => $aboutSchool,
                'status' => 'success'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ]);
        }
    }
}
