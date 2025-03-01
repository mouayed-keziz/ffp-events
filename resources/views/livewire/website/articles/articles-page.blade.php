<?php

use Livewire\Volt\Component;
use App\Models\Article;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'Date';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function with(): array
    {
        return [
            'articles' => Article::query()
                // ->published()
                ->when($this->search, function (Builder $query) {
                    $query->where('title->fr', 'like', '%' . $this->search . '%')->orWhere('description->fr', 'like', '%' . $this->search . '%');
                })
                ->when($this->sortBy === 'Date', fn($query) => $query->orderBy('published_at', 'desc'))
                ->when($this->sortBy === 'Popularité', fn($query) => $query->orderByDesc('views'))
                ->paginate(8),
        ];
    }
}; ?>

<div class="space-y-8">
    <h1 class="text-2xl font-bold">Nos Articles</h1>

    <!-- Search and filters section -->
    <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
        <!-- Search and filters container with max-width -->
        <div class="flex flex-col sm:flex-row gap-4 lg:w-[calc(100%-12rem)]">
            @include('website.components.articles.search')
            @include('website.components.articles.filters')
        </div>

        <!-- Sort dropdown -->
        @include('website.components.articles.sort')
    </div>

    <!-- Articles grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($articles as $article)
            @include('website.components.articles.article-card', [
                'title' => $article->title,
                'slug' => $article->slug,
                'date' => $article->published_at ? $article->published_at->format('d F Y') : '',
                'views' => $article->views,
                'image' => $article->getFirstMediaUrl('image') ?: asset('placeholder.png'),
            ])
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="my-16">
        {{ $articles->links() }}
    </div>
</div>
