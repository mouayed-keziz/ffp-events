<?php

use Livewire\Volt\Component;
use App\Models\Article;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $sortBy = 'newest';

    #[Url]
    public $selectedCategories = [];

    public function mount()
    {
        // Ensure selectedCategories is an array if it comes from query params
        if (!is_array($this->selectedCategories)) {
            $this->selectedCategories = $this->selectedCategories ? [$this->selectedCategories] : [];
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->selectedCategories)) {
            $this->selectedCategories = array_values(array_diff($this->selectedCategories, [$categoryId]));
        } else {
            $this->selectedCategories[] = $categoryId;
        }
    }

    public function with(): array
    {
        return [
            'categories' => Category::all(),
            'articles' => Article::query()
                ->published() // Only get published articles
                ->when(!empty($this->selectedCategories), function (Builder $query) {
                    $query->whereHas('categories', function (Builder $q) {
                        $q->whereIn('categories.id', $this->selectedCategories);
                    });
                })
                ->when($this->search, function (Builder $query) {
                    // Search in all languages by using JSON contains
                    $query->where(function (Builder $q) {
                        $q->whereJsonContains('title', $this->search)
                            ->orWhere('title->en', 'like', '%' . $this->search . '%')
                            ->orWhere('title->fr', 'like', '%' . $this->search . '%')
                            ->orWhere('title->ar', 'like', '%' . $this->search . '%')
                            ->orWhere('description->en', 'like', '%' . $this->search . '%')
                            ->orWhere('description->fr', 'like', '%' . $this->search . '%')
                            ->orWhere('description->ar', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->sortBy === 'newest', fn($query) => $query->orderBy('published_at', 'desc'))
                ->when($this->sortBy === 'oldest', fn($query) => $query->orderBy('published_at', 'asc'))
                ->when($this->sortBy === 'most_viewed', function ($query) {
                    $query->withCount('visits')->orderByDesc('visits_count');
                })
                ->when($this->sortBy === 'most_shared', function ($query) {
                    $query->withCount('shares')->orderByDesc('shares_count');
                })
                ->paginate(8),
        ];
    }
};
?>

<div class="space-y-8">
    <h1 class="text-2xl font-bold">{{ __('website/articles.title') }}</h1>

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
                'date' => $article->published_at,
                'views' => $article->views,
                'shares' => $article->shares_count,
                'image' => $article->getFirstMediaUrl('image') ?: asset('placeholder.png'),
            ])
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="my-16">
        {{ $articles->links() }}
    </div>
</div>
