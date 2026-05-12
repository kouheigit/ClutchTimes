{{-- フィルタードロップダウンコンポーネント --}}
@props(['label' => 'フィルター', 'options' => [], 'selected' => null])

<div x-data="{ open: false }" class="relative inline-block text-left">
    <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        {{ $label }}
        <svg class="-mr-1 ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open" @click.away="open = false" x-transition class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10" style="display: none;">
        <div class="py-1">
            @foreach($options as $option)
            <a href="{{ $option['url'] ?? '#' }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ ($selected ?? null) === $option['value'] ? 'bg-gray-50' : '' }}">
                {{ $option['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>

