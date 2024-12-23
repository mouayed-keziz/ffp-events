<a href="{{ route('blog.category', $category) }}"
    class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow border">
    <div class="card-body">
        <h2 class="card-title">{{ $category->name }}</h2>
        <p class="text-gray-600">{{ $category->articles_count }} articles</p>
    </div>
</a>
