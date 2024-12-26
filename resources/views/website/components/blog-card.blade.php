<a href="{{ route('blog.show', $article) }}" class="block group h-full">
    <div
        class="card bg-base-100 shadow-xl card-bordered group-hover:shadow-2xl transition-all duration-300 group-hover:-translate-y-1 h-full flex flex-col">
        @if ($article->hasMedia('image'))
            <figure class="relative border-b aspect-[16/9] overflow-hidden rounded-box">
                <img src="{{ $article->getFirstMediaUrl('image') }}" alt="{{ $article->title }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
            </figure>
        @else
            <div class="aspect-[16/9] w-full bg-base-200 border-b flex items-center justify-center p-6 rounded-box">
                <h3 class="text-xl font-semibold text-center text-base-content/70">{{ $article->title }}</h3>
            </div>
        @endif
        <div class="card-body gap-4 flex-grow flex flex-col">
            <h2 class="card-title font-bold group-hover:text-primary transition-colors">{{ $article->title }}</h2>
            <p class="text-base-content/70 text-sm">{{ Str::words($article->description, 20, '...') }}</p>
            <div class="flex flex-col gap-3 mt-auto">
                <div class="flex flex-wrap gap-2">
                    @foreach ($article->categories as $category)
                        <span
                            class="badge bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
                <div class="card-actions justify-end">
                    <span
                        class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-primary group-hover:gap-3 transition-all">
                        {{ __('website/blog.read_more') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</a>
