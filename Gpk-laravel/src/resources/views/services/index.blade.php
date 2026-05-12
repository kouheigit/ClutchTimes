<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            サービス一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <!-- 最新予約情報 -->
            @if($last_reservation)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-2">最新の予約</h3>
                <p class="text-sm text-gray-700 mb-2">
                    予約ID: {{ $last_reservation->id }} - 
                    {{ \Carbon\Carbon::parse($last_reservation->checkin_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($last_reservation->checkin_date)->locale('ja')->isoFormat('ddd') }})
                    ～ {{ \Carbon\Carbon::parse($last_reservation->checkout_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($last_reservation->checkout_date)->locale('ja')->isoFormat('ddd') }})
                    ({{ $last_reservation->days }}泊)
                </p>
                <p class="text-xs text-gray-600">
                    この予約に関連付けてサービスを注文できます
                </p>
            </div>
            @endif

            @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
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
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($service->body ?? '', 100) }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-2xl font-bold text-blue-600">
                                ¥{{ number_format($service->price) }}
                            </span>
                            @if($service->unit)
                            <span class="text-sm text-gray-500">
                                / {{ $service->unit }}
                            </span>
                            @endif
                        </div>
                        
                        @if($service->stock > 0)
                        <p class="text-xs text-gray-500 mb-2">
                            在庫: {{ $service->stock }}{{ $service->unit ?? '' }}
                        </p>
                        @elseif($service->stock == 0)
                        <p class="text-xs text-red-600 font-semibold mb-2">
                            在庫切れ
                        </p>
                        @endif
                        
                        @if($service->serviceOptions->count() > 0)
                        <p class="text-xs text-gray-500 mb-4">
                            オプション: {{ $service->serviceOptions->count() }}種類
                        </p>
                        @endif
                        
                        <a href="{{ route('services.show', $service) }}" 
                           class="block w-full text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                            詳細を見る
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-gray-600">サービスがありません</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

