<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $processedPosts = [];
        // $posts = Post::chunk(5, function ($posts) use(&$processedPosts){
        //     foreach ($posts as $post) {
        //         $processedPosts[] = $post;
        //     }
        // });
        // $posts = Post::where('author_id',1)->chunkById(10, function ($flights) {
        //     $flights->each->delete();
        // }, $column = 'id');

        // $posts = Post::where('author_id', 1)
        // ->lazyById(200, $column = 'id')
        // ->each->update(['departed' => false]);
        
        // foreach (Post::lazy() as $post) {
        //     $processedPosts[] = $post;
        // }
        // dd($processedPosts);
        $posts = Post::with('author')->orderBy('id','desc')->get();
        $columns = array('Title','Content');
        return view('posts.index',['data' => $posts, 'columns' => $columns]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            return DB::transaction(function () use($request) {
                $validator = Validator::make($request->only(['title','body']), [
                    'title' => 'required',
                    'body' => 'required',
                ]);
         
                if ($validator->fails()) {
                   return response(['errors' => $validator->errors()->toArray()],422);
                }
                $data = $validator->validated();
                $data['author_id'] = 2;
    
                $post = Post::create($data);
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    // Handle the file upload here (e.g., store the file, generate a path, etc.)
                    $filePath = $file->store('uploads', 'public');
                    $data['file_path'] = $filePath;
                }
                // Return a JSON response with the created post data
                return response()->json([
                    'success' => true,
                    'message' => 'Post created successfully.',
                    'post' => $post
                ], 201); // 201 status code for a successful creation
            }, 5);
    
        } catch (\Exception $e) {
            // Return a JSON response with an error message
            return response()->json([
                'success' => false,
                'message' => 'Failed to create post. Please try again.'
            ], 500); // 500 status code for server error
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        dd($post,'SHOW');
        // return view('post', [
        //     'post' => Post::where('slug', $slug)->first()
        // ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        return DB::transaction(function () use($request, $post) {
            $post->update($request->validated());

            return redirect()->route('posts.index');
        }, 5);
     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Post $post)
    {
        $post->delete();
        return response(['success' => true, 'message' => 'Post deleted successfully']);
    }
}
