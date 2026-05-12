{{-- クイックアクションボタンコンポーネント --}}
@props(['href' => '#', 'icon' => null])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']) }}>
    @if($icon)
    <x-icon :name="$icon" size="sm" class="mr-2" />
    @endif
    {{ $slot }}
</a>

