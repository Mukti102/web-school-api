<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        return response()->json(['data' => \App\Models\Visitor::all()]);
    }
}
