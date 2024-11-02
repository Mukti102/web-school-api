<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileSchoolResource;
use App\Models\ProfileSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schoolData = ProfileSchool::first()->get();
        return ProfileSchoolResource::collection($schoolData);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfileSchool $profileSchool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileSchool $profileSchool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProfileSchool $profileSchool)
    {
        try {
            if ($request->hasFile('logo')) {
                if (Storage::disk('public')->exists($profileSchool->logo)) {
                    Storage::disk('public')->delete($profileSchool->logo);
                }
                $profileSchool->logo = $request->file('logo')->store('profileSchool', 'public');
            }

            if ($request->hasFile('ttd_lead_of_school')) {
                if (Storage::disk('public')->exists($profileSchool->ttd_lead_of_school)) {
                    Storage::disk('public')->delete($profileSchool->ttd_lead_of_school);
                }
                $profileSchool->ttd_lead_of_school = $request->file('ttd_lead_of_school')->store('profileSchool', 'public');
            }

            if ($request->hasFile('ttd_ketua_panitia')) {
                if (Storage::disk('public')->exists($profileSchool->ttd_ketua_panitia)) {
                    Storage::disk('public')->delete($profileSchool->ttd_ketua_panitia);
                }
                $profileSchool->ttd_ketua_panitia = $request->file('ttd_ketua_panitia')->store('profileSchool', 'public');
            }
            $profileSchool->save();
            return response()->json(['status' => 'success', 'data' => $profileSchool], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfileSchool $profileSchool)
    {
        //
    }
}
