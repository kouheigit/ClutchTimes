{{-- タイムラインアイテムコンポーネント --}}
@props(['date' => null, 'icon' => null])

<div class="relative flex items-start">
    <div class="absolute left-4 w-8 h-8 bg-white rounded-full border-2 border-gray-200 flex items-center justify-center">
        @if($icon)
            <x-icon :name="$icon" size="sm" />
        @else
            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
        @endif
    </div>
    <div class="ml-12 flex-1">
        @if($date)
        <div class="text-sm text-gray-500 mb-1">{{ $date }}</div>
        @endif
        <div class="text-gray-900">
            {{ $slot }}
        </div>
    </div>
</div>

