@props(['article'])
<div class="flex flex-wrap gap-2">
    @foreach ($article->categories as $category)
        <span
            class="cursor-pointer bg-gray-200 text-gray-800 text-xs font-semibold mr-2 px-3 py-2 rounded-btn
                hover:bg-gray-300 hover:text-gray-900 transition-colors duration-200 ease-in-out
            ">{{ $category->name }}</span>
    @endforeach
</div>
