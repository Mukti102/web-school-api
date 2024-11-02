<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit');
        $tagsname = $request->query('tagsname');

        $query = Article::latest();

        if ($tagsname) {
            $query->where('tags', 'like', '%' . $tagsname . '%')->paginate(5);
        }

        if ($limit === 'all') {
            $articles = $query->get();
        } else if ($limit) {
            $articles = $query->take($limit)->get();
        } else {
            $articles = $query->paginate(5);
        }

        return ArticleResource::collection($articles);
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
            // Define validation rules and messages
            $validatedData = $request->validate([
                'author' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'tags' => 'array', // Pastikan tags berupa array
                'tags.*' => 'string', // Setiap item tag harus berupa string
                'thumbnail' => 'file|mimes:jpeg,png,jpg|max:2048', // Validasi thumbnail
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'author.string' => 'Penulis harus berupa teks.',
                'author.max' => 'Penulis tidak boleh lebih dari 255 karakter.',
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',
                'tags.array' => 'Tags harus berupa array.',
                'category_article_id.required' => "Category Tidak Boleh Kosong",
                'category_article_id.exists' => "Kategori tidak valid.",
                'thumbnail.file' => 'Thumbnail harus berupa file.',
                'thumbnail.mimes' => 'Thumbnail harus dalam format jpeg, png, atau jpg.',
                'thumbnail.max' => 'Thumbnail tidak boleh lebih dari 2MB.',
            ]);

            // Simpan file thumbnail jika ada
            $thumbnailPath = null;
            if ($request->file('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('articles', 'public');
            }

            // Buat artikel baru dengan data yang telah divalidasi
            $article = Article::create([
                'author' => $validatedData['author'],
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'tags' => json_encode($validatedData['tags']), // Simpan tags sebagai JSON
                'thumbnail' => $thumbnailPath, // Path thumbnail
            ]);

            // Kembalikan respon sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Article created successfully',
                'data' => $article,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Temukan artikel berdasarkan ID
            $article = Article::findOrFail($id);

            // Define validation rules and messages
            $validatedData = $request->validate([
                'author' => 'sometimes|required|string|max:255',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'tags' => 'sometimes|array', // Pastikan tags berupa array
                'tags.*' => 'string', // Setiap item tag harus berupa string
                'thumbnail' => 'sometimes|max:2048', // Validasi thumbnail
            ], [
                'author.required' => 'Penulis wajib diisi.',
                'author.string' => 'Penulis harus berupa teks.',
                'author.max' => 'Penulis tidak boleh lebih dari 255 karakter.',
                'title.required' => 'Judul wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
                'description.required' => 'Deskripsi wajib diisi.',
                'description.string' => 'Deskripsi harus berupa teks.',
                'tags.array' => 'Tags harus berupa array.',
                'category_article_id.required' => "Category Tidak Boleh Kosong",
                'category_article_id.exists' => "Kategori tidak valid.",
                'thumbnail.file' => 'Thumbnail harus berupa file.',
                'thumbnail.mimes' => 'Thumbnail harus dalam format jpeg, png, atau jpg.',
                'thumbnail.max' => 'Thumbnail tidak boleh lebih dari 2MB.',
            ]);

            // Simpan file thumbnail jika ada
            $thumbnailPath = $article->thumbnail; // Ambil path thumbnail yang sudah ada
            if ($request->file('thumbnail')) {
                // Jika ada thumbnail baru, simpan dan update path
                $thumbnailPath = $request->file('thumbnail')->store('articles', 'public');
            }

            // Perbarui artikel dengan data yang telah divalidasi
            $article->update([
                'author' => $validatedData['author'] ?? $article->author,
                'title' => $validatedData['title'] ?? $article->title,
                'description' => $validatedData['description'] ?? $article->description,
                'tags' => isset($validatedData['tags']) ? json_encode($validatedData['tags']) : $article->tags, // Simpan tags sebagai JSON
                'thumbnail' => $thumbnailPath, // Path thumbnail
            ]);

            // Kembalikan respon sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Article updated successfully',
                'data' => $article,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validated Fail', 'errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            if ($article->thumbnail) {
                $thumbnailPath = $article->thumbnail;
                if (Storage::disk('public')->exists($thumbnailPath)) {
                    if (!Storage::disk('public')->delete($thumbnailPath)) {
                        throw new \Exception('Failed to delete old file');
                    }
                }
            }
            // return response()->json($article);

            Article::destroy($article->id);
            return response()->json(['status' => 'success', 'message' => 'deleted is successfull'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'deleted is fail because ' . $th->getMessage()], 500);
        }
    }
}
