<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, PostService $postService)
    {
        $validated = $request->validated();
        $postService->create($request->user(), $validated);

        return back()->with('success', 'Post created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        Gate::authorize('update', $post);
        $post->load(['user', 'images']);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post, PostService $postService)
    {
        Gate::authorize('update', $post);
        $validated = $request->validated();
        $post = $postService->update($post, $validated);

        return back()->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, PostService $postService)
    {
        Gate::authorize('delete', $post);
        $postService->delete($post);
        return back()->with('success', 'Post deleted successfully');
    }
}
