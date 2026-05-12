{{-- 戻るリンクコンポーネント --}}
@props(['href' => '#', 'text' => '戻る'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center text-gray-600 hover:text-gray-900']) }}>
    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    {{ $text }}
</a>

