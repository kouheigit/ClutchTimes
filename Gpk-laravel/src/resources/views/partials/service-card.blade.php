@props(['service'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
    @if($service->image)
    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
    @else
    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
        <span class="text-gray-400">No Image</span>
    </div>
    @endif
    
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-2">{{ $service->title }}</h3>
        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->body, 100) }}</p>
        
        <div class="flex justify-between items-center mb-4">
            <x-price-display :price="$service->price" :unit="$service->unit ?? '個'" />
        </div>
        
        @if($service->stock > 0)
        <p class="text-xs text-gray-500 mb-4">在庫: {{ $service->stock }}{{ $service->unit ?? '個' }}</p>
        @elseif($service->stock == 0)
        <p class="text-xs text-red-500 mb-4 font-semibold">在庫切れ</p>
        @endif
        
        @if($service->serviceOptions && $service->serviceOptions->count() > 0)
        <p class="text-xs text-gray-500 mb-4">{{ $service->serviceOptions->count() }}種類のオプションあり</p>
        @endif
        
        <a href="{{ route('services.show', $service) }}" 
           class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            詳細を見る
        </a>
    </div>
</div>




















