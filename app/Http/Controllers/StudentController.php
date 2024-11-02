<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('user_id')) {
            $students = Student::with('parent', 'adress', 'fromSchool', 'jurusan', 'angkatan', 'user')
                ->whereHas('user', function ($query) use ($request) {
                    $query->where('id', $request->user_id);
                })
                ->get();

            if ($students->isEmpty()) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            return StudentResource::collection($students);
        }

        // Retrieve all students instead of just the first one
        $students = Student::with('parent', 'adress', 'fromSchool', 'jurusan', 'angkatan', 'user')->get();

        // Use the StudentResource to transform the collection
        return StudentResource::collection($students);
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
            $studentData = $request->userInfo;
            $adressData = $request->addressInfo;
            $parentData = $request->parentInfo;
            $fromSchoolData = $request->previousSchoolInfo;

            // Ensure `$studentData` is an array
            if (is_array($studentData) && isset($studentData['photo'])) {
                $studentData['photo'] = $studentData['photo']->store('student', 'public');
            } elseif (is_object($studentData) && $studentData->photo) {
                $studentData->photo = $studentData->file('photo')->store('student', 'public');
            }

            // return response()->json($studentData);
            $student = Student::create($studentData);
            $student->adress()->create($adressData);
            $student->parent()->create($parentData);
            $student->fromSchool()->create($fromSchoolData);
            return response()->json(['message' => 'Student created successfully', 'status' => 'success', 'data' => $student], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $students = $student->load('parent', 'adress', 'fromSchool', 'jurusan', 'angkatan');
        return new StudentResource($students);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        try {
            $studentData = Arr::except($request->userInfo, ['created_at', 'updated_at']);
            $addressData = Arr::except($request->addressInfo, ['created_at', 'updated_at']);
            $parentData = Arr::except($request->parentInfo, ['created_at', 'updated_at']);
            $fromSchoolData = Arr::except($request->previousSchoolInfo, ['created_at', 'updated_at']);

            // Handle photo upload only if there's a new file in the request
            if ($request->hasFile('userInfo.photo')) {
                if (Storage::disk('public')->exists($student->photo)) {
                    Storage::disk('public')->delete($student->photo);  // Delete the old photo if it exists
                }
                $studentData['photo'] = $request->file('userInfo.photo')->store('student', 'public'); // Store new photo
            } else {
                // Keep the existing photo path if no new file is uploaded
                $studentData['photo'] = $student->photo;
            }

            // Update the student and related models
            $student->update($studentData);
            $student->adress()->update($addressData);
            $student->parent()->update($parentData);
            $student->fromSchool()->update($fromSchoolData);

            return response()->json([
                'message' => 'Student updated successfully',
                'status' => 'success',
                'data' => $student
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if (Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        if (Storage::disk('public')->exists($student->scan_kk)) {
            Storage::disk('public')->delete($student->scan_kk);
        }

        if (Storage::disk('public')->exists($student->scan_ijazah)) {
            Storage::disk('public')->delete($student->scan_ijazah);
        }

        if (Storage::disk('public')->exists($student->scan_skhun)) {
            Storage::disk('public')->delete($student->scan_skhun);
        }

        $student->delete();
        return response()->json(['message' => 'Student deleted successfully', 'status' => 'success'], 200);
    }

    public function accept($id)
    {
        $student = Student::find($id);
        $student->update([
            'status' => 'di terima'
        ]);
        return response()->json(['message' => 'Student accepted successfully', 'status' => 'success', 'data' => $student], 200);
    }

    public function reject($id)
    {
        $student = Student::find($id);
        $student->update([
            'status' => 'tidak di terima'
        ]);
        return response()->json(['message' => 'Student rejected successfully', 'status' => 'success', 'data' => $student], 200);
    }
}
