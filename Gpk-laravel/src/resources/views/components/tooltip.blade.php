{{-- ツールチップコンポーネント --}}
@props(['text'])

<div class="relative group">
    {{ $slot }}
    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10">
        <div class="bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
            {{ $text }}
            <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
        </div>
    </div>
</div>

