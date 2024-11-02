<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranPageController extends Controller
{
    public function index()
    {
        $halamanUtama = PendaftaranPage::all()->first();
        return response()->json(['data' => [
            'id' => $halamanUtama->id,
            'title' => $halamanUtama->title,
            'description' => $halamanUtama->description,
            'background' => env('ENDPOINT') . 'storage/' . $halamanUtama->background,
        ]], 200);
    }

    public function update($id, Request $request)
    {
        $pendaftaranPage = PendaftaranPage::find($id);
        try {
            $credentials = request()->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'background' => 'required',
            ], [
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',
                'background.required' => 'Gambar wajib diisi.',
            ]);

            if ($request->hasFile('background')) {
                if (Storage::disk('public')->exists($pendaftaranPage->background)) {
                    Storage::disk('public')->delete($pendaftaranPage->background);
                }
                $credentials['background'] = $request->file('background')->store('pendaftaran-page', 'public');
            }
            $pendaftaranPage->update($credentials);
            return response()->json(['data' => $pendaftaranPage, 'status' => 'success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        $pendaftaranPage = PendaftaranPage::find($id);
        if ($pendaftaranPage->background) {
            if (Storage::disk('public')->exists($pendaftaranPage->background)) {
                Storage::disk('public')->delete($pendaftaranPage->background);
            }
        }
        $pendaftaranPage->delete();
        return response()->json(['status' => 'success', 'message' => 'Pendaftaran Page Deleted'], 200);
    }
}
