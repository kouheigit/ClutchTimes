<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ホテル一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if($hotels->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hotels as $hotel)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">
                            <a href="{{ route('hotels.show', $hotel) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $hotel->name }}
                            </a>
                        </h3>
                        
                        @if($hotel->address)
                        <p class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}
                        </p>
                        @endif
                        
                        @if($hotel->description)
                        <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                            {{ Str::limit($hotel->description, 100) }}
                        </p>
                        @endif
                        
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xs text-gray-500">
                                @if($hotel->services->count() > 0)
                                    {{ $hotel->services->count() }}件のサービス
                                @endif
                            </span>
                            <a href="{{ route('hotels.show', $hotel) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                詳細を見る
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    ホテルが見つかりませんでした。
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

