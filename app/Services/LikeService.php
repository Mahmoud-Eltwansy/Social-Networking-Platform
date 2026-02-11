<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class LikeService
{
    public function toggle(User $user, Post $post): Bool
    {
        return DB::transaction(function () use ($user, $post) {
            $existing = $post->likes()->where('user_id', $user->id)->first();

            if ($existing) {
                $existing->delete();
                $liked = false;
            } else {
                $post->likes()->create([
                    'user_id' => $user->id,
                ]);
                $liked = true;
            }
            return $liked;
        });
    }
}
