{{-- セクションコンポーネント --}}
@props(['title' => null, 'subtitle' => null])

<section {{ $attributes->merge(['class' => 'py-12']) }}>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if($title || $subtitle)
        <div class="mb-6">
            @if($title)
            <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
            @endif
            @if($subtitle)
            <p class="mt-2 text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
        @endif
        {{ $slot }}
    </div>
</section>

