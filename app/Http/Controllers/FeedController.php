<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'images',])->latest()->paginate(10);
        return view('feed', compact('posts'));
    }
}
