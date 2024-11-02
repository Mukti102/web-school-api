<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Contracts\Service\Attribute\Required;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::all();
        return TeacherResource::collection($teachers);
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
                'name' => 'required|string|max:255',
                'TTL' => 'required|string',
                'religion' => 'required|string|max:100',
                'gender' => 'required|in:laki-laki,perempuan',
                'phone' => 'required|string|max:20',
                'status' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:users,email',
                'position' => 'required|string|max:100',
                'address' => 'required|string|max:255',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                'TTL.required' => 'Tanggal lahir wajib diisi.',
                'TTL.date' => 'Tanggal lahir harus berupa tanggal yang valid.',

                'religion.required' => 'Agama wajib diisi.',
                'religion.string' => 'Agama harus berupa teks.',
                'religion.max' => 'Agama tidak boleh lebih dari 100 karakter.',

                'gender.required' => 'Jenis kelamin wajib diisi.',
                'gender.in' => 'Jenis kelamin harus laki-laki atau perempuan.',

                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon harus berupa teks.',
                'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

                'status.required' => 'Status wajib diisi.',
                'status.string' => 'Status harus berupa teks.',
                'status.max' => 'Status tidak boleh lebih dari 100 karakter.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
                'email.unique' => 'Alamat email ini sudah terdaftar.',

                'position.required' => 'Posisi wajib diisi.',
                'position.string' => 'Posisi harus berupa teks.',
                'position.max' => 'Posisi tidak boleh lebih dari 100 karakter.',

                'address.required' => 'Alamat wajib diisi.',
                'address.string' => 'Alamat harus berupa teks.',
                'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
            ]);
            if ($request->hasFile('photo')) {
                $credentials['photo'] = $request->file('photo')->store('teachers', 'public');
            } else {
                $credentials['photo'] = null;
            }
            Teacher::create($credentials);
            return response()->json(['message' => 'success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return new TeacherResource($teacher);
    }

    public function edit(Teacher $teacher)
    {
        //
    }

    public function update(Request $request, Teacher $teacher)
    {
        try {
            $credentials = $request->validate([
                'name' => 'required|string|max:255',
                'TTL' => 'required|date',
                'religion' => 'required|string|max:100',
                'gender' => 'required|in:laki-laki,perempuan',
                'phone' => 'required|string|max:20',
                'status' => 'required|string|max:100',
                'email' => 'required|email|max:255',
                'position' => 'required|string|max:100',
                'address' => 'required|string|max:255',
            ], [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',

                'TTL.required' => 'Tanggal lahir wajib diisi.',
                'TTL.date' => 'Tanggal lahir harus berupa tanggal yang valid.',

                'religion.required' => 'Agama wajib diisi.',
                'religion.string' => 'Agama harus berupa teks.',
                'religion.max' => 'Agama tidak boleh lebih dari 100 karakter.',

                'gender.required' => 'Jenis kelamin wajib diisi.',
                'gender.in' => 'Jenis kelamin harus laki-laki atau perempuan.',

                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.string' => 'Nomor telepon harus berupa teks.',
                'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

                'status.required' => 'Status wajib diisi.',
                'status.string' => 'Status harus berupa teks.',
                'status.max' => 'Status tidak boleh lebih dari 100 karakter.',

                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email harus berupa alamat email yang valid.',
                'email.max' => 'Email tidak boleh lebih dari 255 karakter.',

                'position.required' => 'Posisi wajib diisi.',
                'position.string' => 'Posisi harus berupa teks.',
                'position.max' => 'Posisi tidak boleh lebih dari 100 karakter.',

                'address.required' => 'Alamat wajib diisi.',
                'address.string' => 'Alamat harus berupa teks.',
                'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
            ]);
            // delete old photo
            if ($teacher->photo) {
                if (Storage::disk('public')->exists($teacher->photo)) {
                    Storage::disk('public')->delete($teacher->photo);
                } else {
                    $credentials['photo'] = $teacher->photo;
                }
            }
            $teacher->update($credentials);
            return response()->json(['message' => 'success'], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            if ($teacher->photo) {
                if (Storage::disk('public')->exists($teacher->photo)) {
                    Storage::disk('public')->delete($teacher->photo);
                }
            }
            Teacher::destroy($teacher->id);
            return response(['message' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
