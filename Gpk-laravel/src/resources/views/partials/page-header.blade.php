{{-- ページヘッダーパーシャル --}}
@props(['title' => null, 'subtitle' => null, 'actions' => null])

<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            @if($title)
            <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
            @endif
            @if($subtitle)
            <p class="mt-2 text-sm text-gray-600">{{ $subtitle }}</p>
            @endif
        </div>
        @if($actions)
        <div class="flex items-center space-x-3">
            {{ $actions }}
        </div>
        @endif
    </div>
</div>

