<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentService
{
    public function create(User $user, Post $post, array $data): Comment
    {
        return $post->comments()->create([
            'user_id' => $user->id,
            'content' => $data['content']
        ]);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
