<div class="card bg-base-100 shadow-xl card-bordered">
    @if ($article->hasMedia('image'))
        <figure class="border-b">
            <img src="{{ $article->getFirstMediaUrl('image') }}" alt="{{ $article->title }}"
                class="h-48 w-full object-cover" />
        </figure>
    @else
        <div class="h-48 w-full bg-base-200 border-b flex items-center justify-center p-4">
            <h3 class="text-lg font-semibold text-center text-base-content/70">{{ $article->title }}</h3>
        </div>
    @endif
    <div class="card-body">
        <h2 class="card-title">{{ $article->title }}</h2>
        <p class="text-gray-600 line-clamp-2">{{ $article->description }}</p>
        <div class="card-actions justify-between items-center mt-4">
            <div class="flex gap-2">
                @foreach ($article->categories as $category)
                    <a href="{{ route('blog.category', $category) }}"
                        class="badge badge-outline">{{ $category->name }}</a>
                @endforeach
            </div>
            <a href="{{ route('blog.show', $article) }}" class="btn btn-primary btn-sm">Read More</a>
        </div>
    </div>
</div>
