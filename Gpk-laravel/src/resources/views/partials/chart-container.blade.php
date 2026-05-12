{{-- チャートコンテナパーシャル --}}
@props(['title' => null, 'type' => 'line'])

<div class="bg-white shadow rounded-lg p-6">
    @if($title)
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
    @endif
    <div class="chart-container" data-chart-type="{{ $type }}">
        {{ $slot }}
    </div>
</div>

