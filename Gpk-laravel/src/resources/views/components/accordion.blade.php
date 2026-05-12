{{-- アコーディオンコンポーネント --}}
@props(['open' => false])

<div x-data="{ open: {{ $open ? 'true' : 'false' }} }" {{ $attributes }}>
    <button @click="open = !open" class="w-full flex justify-between items-center py-2 text-left">
        <span class="font-medium">{{ $title ?? 'タイトル' }}</span>
        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" x-collapse class="mt-2">
        {{ $slot }}
    </div>
</div>

