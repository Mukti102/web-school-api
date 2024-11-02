<?php

namespace App\Http\Controllers;

use App\Models\category_article;
use Illuminate\Http\Request;

class CategoryArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category_articles = category_article::all();
        return response()->json($category_articles, 200);
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
    public function show(category_article $category_article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category_article $category_article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category_article $category_article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category_article $category_article)
    {
        //
    }
}
