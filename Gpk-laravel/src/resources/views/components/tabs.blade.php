{{-- タブコンポーネント --}}
@props(['tabs' => [], 'active' => null])

<div class="border-b border-gray-200">
    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
        @foreach($tabs as $tab)
        <a href="{{ $tab['url'] ?? '#' }}" 
           class="@if(($active ?? $loop->first) === $loop->first) border-blue-500 text-blue-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            {{ $tab['label'] }}
        </a>
        @endforeach
    </nav>
</div>

