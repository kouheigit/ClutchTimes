@props(['traffic'])

@if($traffic && isset($traffic['routes']) && count($traffic['routes']) > 0)
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">交通情報</h3>
        <div class="space-y-3">
            @foreach($traffic['routes'] as $route)
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-medium">{{ $route['summary'] ?? 'ルート' }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            距離: {{ $route['distance'] ?? 'N/A' }} / 
                            時間: {{ $route['duration'] ?? 'N/A' }}
                        </p>
                        @if(isset($route['warnings']) && count($route['warnings']) > 0)
                        <div class="mt-2">
                            @foreach($route['warnings'] as $warning)
                            <p class="text-xs text-yellow-600">⚠️ {{ $warning }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif




















