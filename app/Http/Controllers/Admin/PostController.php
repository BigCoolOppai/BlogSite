<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            
        ]);
        $data = $request->all();
        
        $data['thumbnail'] = Post::uploadImage($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = Post::uploadImage($request);
        } else {
            //$data['thumbnail'] = 'path/to/default.jpg';
        }
        $post = Post::create($data);
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }
        return redirect()->route('posts.index')->with('success', 'Статья добавлена');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::pluck('title', 'id')->all();
        $tags = Tag::pluck('title', 'id')->all();
        return view('admin.posts.edit', compact('post', 'categories', 'tags')); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ]);
        $data = $request->all();
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = Post::uploadImage($request, $post->thumbnail);
        } else {
            $data['thumbnail'] = $post->thumbnail;
        }
        $post->update($data);
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->sync([]);
        }

        return redirect()->route('posts.index')->with('success', 'ИЗМЕНЕНИЯ СОХРАНЕНЫ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->sync([]);
        if ($post->thumbnail) {
            Storage::delete($post->thumbnail);
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Статья удалена');
    }
}
