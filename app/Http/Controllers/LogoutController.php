<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {

        // Retrieve the authenticated user
        $user = Auth::user() ?? User::find($request->id);
        if ($user) {
            // Delete all tokens for the user
            $user->tokens()->delete(); // Ensure the method is called on the relationship

            return response()->json(['message' => 'Logged out successfully', 'status' => 'success'], 200);
        }
        return response()->json(['message' => "User Is Not Authenticated", $request->all()], 401);
    }
}
