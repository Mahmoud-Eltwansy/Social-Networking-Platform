<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Feed</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        {{-- Create Post --}}
        <div class="p-4 bg-white rounded shadow">
            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf

                <textarea name="content" class="border rounded p-2 w-full" placeholder="What's on your mind?">{{ old('content') }}</textarea>
                @error('content')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input type="file" name="images[]" multiple accept="image/*" />
                @error('images')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
                @error('images.*')
                    <div class="text-red-600 text-sm ">{{ $message }}</div>
                @enderror

                <button class="px-4 py-2 bg-gray-800 text-white rounded">Post</button>
            </form>
        </div>

        {{-- Posts --}}
        @foreach ($posts as $post)
            <div class="p-4 bg-white rounded shadow space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <a class="font-semibold" href="">{{ $post->user->name }}</a>
                        <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                    </div>

                    @if (auth()->id() === $post->user_id)
                        <div class="flex gap-2">
                            <a class="text-blue-600" href="{{ route('posts.edit', $post) }}">Edit</a>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                @csrf @method('DELETE')
                                <button class="text-red-600" onclick="return confirm('Delete post?')">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div>{{ $post->content }}</div>

                @if ($post->images->count())
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($post->images as $img)
                            <img class="rounded border" src="{{ asset('storage/' . $img->path) }}" alt="post image">
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center gap-4 text-sm">
                    <form method="POST" action="{{ route('posts.like', $post) }}">
                        @csrf
                        @if ($post->isLikedBy(auth()->user()))
                            <button class="px-3 py-1 border rounded bg-blue-600 text-white">Liked
                                ({{ $post->likes_count }})</button>
                        @else
                            <button class="px-3 py-1 border rounded">Like ({{ $post->likes_count }})</button>
                        @endif

                    </form>
                    <span>Comments: {{ $post->comments_count }}</span>
                </div>

                {{-- Comments --}}
                <div class="space-y-2">
                    <form method="POST" action="{{ route('comments.store', $post) }}" class="flex gap-2">
                        @csrf
                        <input name="content" class="flex-1 border rounded p-2" placeholder="Write a comment...">
                        <button class="px-3 py-2 bg-gray-800 text-white rounded">Send</button>
                    </form>
                    @error('content')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror

                    @foreach ($post->comments as $comment)
                        <div class="flex justify-between border rounded p-2">
                            <div>
                                <span class="font-semibold">{{ $comment->user->name }}:</span>
                                <span>{{ $comment->content }}</span>
                            </div>

                            @if (auth()->id() === $comment->user_id)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 text-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{ $posts->links() }}
    </div>
</x-app-layout>
