<?php

namespace App\Http\Controllers;

use App\Http\Resources\PendidikanPageResource;
use App\Models\PendidikanPage;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendidikanPageController extends Controller
{
    public function index()
    {
        return PendidikanPageResource::collection(PendidikanPage::all()->first()->get());
    }

    public function update($id, Request $request)
    {
        $PendidikanPage = PendidikanPage::find($id);

        if (!$PendidikanPage) {
            return response()->json(["status" => 'error', "message" => "PendidikanPage not found"], 404);
        }

        try {
            $credentidals = $request->validate([
                'title' => "required|max:200",
                'description' => "required",
                'potret_photo' => "nullable",
                'lanscape_photo' => "nullable",
                'thumbnail' => "nullable",
            ], [
                'title.required' => "Harus Di isi",
                'description.required' => "Harus Di isi",
                'potret_photo.required' => "Harus Di isi",
                'lanscape_photo.required' => "Harus Di isi",
                'thumbnail.required' => "Harus Di isi",
            ]);

            if ($request->hasFile('potret_photo')) {
                if ($PendidikanPage->potret_photo && Storage::disk('public')->exists($PendidikanPage->potret_photo)) {
                    Storage::disk('public')->delete($PendidikanPage->potret_photo);
                }
                $credentidals['potret_photo'] = $request->file('potret_photo')->store('pendidikanPages', 'public');
            }

            if ($request->hasFile('lanscape_photo')) {
                if ($PendidikanPage->lanscape_photo && Storage::disk('public')->exists($PendidikanPage->lanscape_photo)) {
                    Storage::disk('public')->delete($PendidikanPage->lanscape_photo);
                }
                $credentidals['lanscape_photo'] = $request->file('lanscape_photo')->store('pendidikanPages', 'public');
            }

            if ($request->hasFile('thumbnail')) {
                if ($PendidikanPage->thumbnail && Storage::disk('public')->exists($PendidikanPage->thumbnail)) {
                    Storage::disk('public')->delete($PendidikanPage->thumbnail);
                }
                $credentidals['thumbnail'] = $request->file('thumbnail')->store('pendidikanPages', 'public');
            }

            $PendidikanPage->update($credentidals);

            return response()->json(['data' => $PendidikanPage, 'status' => 'success']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => "Validation Failed", 'errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            return response()->json(["status" => 'error', "message" => $e->getMessage()], 500);
        }
    }
}
