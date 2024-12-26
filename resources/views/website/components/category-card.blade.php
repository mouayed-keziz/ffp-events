<a href="{{ route('blog.category', $category) }}"
    class="group block overflow-hidden rounded-xl bg-gradient-to-br from-base-100 to-base-200 shadow-md hover:shadow-xl transition-all duration-300 border border-base-300 hover:border-primary hover:-translate-y-1">
    <div class="p-6">
        <h2 class="text-1xl font-bold text-base-content group-hover:text-primary transition-colors mb-2">
            {{ $category->name }}
        </h2>
        <div class="flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <p class="text-base-content/70 font-medium">
                {{ $category->articles_count }}
                {{ trans_choice('website/blog.articles', $category->articles_count) }}
            </p>
        </div>
    </div>
</a>
