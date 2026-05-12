{{-- 機能ハイライトパーシャル --}}
@props(['features' => []])

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @foreach($features as $feature)
    <div class="bg-white p-6 rounded-lg shadow">
        @if(isset($feature['icon']))
        <div class="mb-4">
            <x-icon :name="$feature['icon']" size="xl" class="text-blue-600" />
        </div>
        @endif
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
        <p class="text-gray-600">{{ $feature['description'] }}</p>
        @if(isset($feature['url']))
        <a href="{{ $feature['url'] }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 text-sm font-medium">
            詳細を見る →
        </a>
        @endif
    </div>
    @endforeach
</div>

