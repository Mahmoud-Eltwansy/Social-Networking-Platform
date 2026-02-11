<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Post</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto space-y-4">
        <div class="p-4 bg-white rounded shadow">
            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data"
                class="space-y-3">
                @csrf @method('PUT')

                <textarea name="content" class="w-full border rounded p-2" rows="4">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                @if ($post->images->count())
                    <div class="space-y-2">
                        <div class="font-semibold">Existing Images (select to remove):</div>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($post->images as $img)
                                <label class="border rounded p-2 block">
                                    <input type="checkbox" name="remove_image_ids[]" value="{{ $img->id }}">
                                    <img class="mt-2 rounded" src="{{ asset('storage/' . $img->path) }}">
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <div class="font-semibold">Add new images:</div>
                    <input type="file" name="images[]" multiple accept="image/*" />
                    @error('images.*')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
            </form>
        </div>
    </div>
</x-app-layout>
