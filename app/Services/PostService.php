<?php


namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function create(User $user, array $data): Post
    {
        return DB::transaction(function () use ($user, $data) {
            $post = $user->posts()->create([
                'content' => $data['content']
            ]);
            $this->createImages($post, $data['images']);

            return $post->load(['user', 'images']);
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $post->update([
                'content' => $data['content']
            ]);
            if (!empty($data['remove_image_ids'])) {
                $images = $post->images()->whereIn('id', $data['remove_image_ids'])->get();
                foreach ($images as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
            $this->createImages($post, $data['images'] ?? []);

            return $post->load(['user', 'images']);
        });
    }

    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post) {
            $post->images()->each(function ($image) {
                Storage::disk('public')->delete($image->path);
            });
            $post->delete();
        });
    }

    private function createImages(Post $post, array $images): void
    {
        if (empty($images)) return;
        foreach ($images as $image) {
            $path = $image->store('posts', 'public');
            $post->images()->create([
                'path' => $path
            ]);
        }
    }
}
