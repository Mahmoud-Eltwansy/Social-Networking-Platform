<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post, CommentService $commentService)
    {
        $validated = $request->validated();
        $commentService->create($request->user(), $post, $validated);
        return back();
    }

    public function destroy(Comment $comment, CommentService $commentService)
    {
        Gate::authorize('delete', $comment);
        $commentService->delete($comment);
        return back();
    }
}
