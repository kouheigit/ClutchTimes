{{-- タイムラインコンポーネント --}}
<div {{ $attributes->merge(['class' => 'relative']) }}>
    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
    <div class="space-y-6">
        {{ $slot }}
    </div>
</div>

