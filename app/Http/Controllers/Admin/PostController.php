<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        $posts = Post::query();
        if ($request->has('category')) {
            $posts = $posts->where('category_id', $request->input('category'));
        }
        $posts = $posts->get();
        return view('admin.posts.index',
            compact('categories', 'posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Handle the image upload if it exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts/images', 'public');
        }

        // Create a new post
        Post::create([
            'title' => $request->input('title'),
            'subtitle' => $request->input('subtitle'),
            'label' => $request->input('label'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
            'image' => $imagePath,
            'links' =>[
                'apple' =>$request->input('apple_link') ?? null,
                'spotify' =>$request->input('spotify_link') ?? null,
                'google' => $request->input('google_link') ?? null,
                'amazon' => $request->input('amazon_link') ?? null,
            ],
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Post created successfully!');
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
        $categories = Category::all();
        $post = Post::find($id);
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find the post by ID
        $post = Post::findOrFail($id);

        // Update post data
        $post->title = $request->title;
        $post->subtitle = $request->subtitle;
        $post->label = $request->label;
        $post->content = $request->content;
        $post->category_id = $request->category_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($post->image) {
                Storage::delete($post->image);
            }
            // Store new image and update post image path
            $post->image = $request->file('image')->store('posts/images', 'public');
        }

        // Save the updated post
        $post->save();

        // Redirect with success message
        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Post::find($id)->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully!');

    }
}
