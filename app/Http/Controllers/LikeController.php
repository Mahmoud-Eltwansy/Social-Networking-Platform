<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request, Post $post, LikeService $likeService)
    {
        $likeService->toggle($request->user(), $post);
        return back();
    }
}
