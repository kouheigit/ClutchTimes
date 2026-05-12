<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            検索結果
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 検索フォーム -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('search.index') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="keyword" value="{{ $keyword }}" 
                                   placeholder="キーワードを入力" 
                                   class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <select name="type" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>すべて</option>
                                <option value="hotel" {{ $type == 'hotel' ? 'selected' : '' }}>ホテル</option>
                                <option value="service" {{ $type == 'service' ? 'selected' : '' }}>サービス</option>
                                <option value="reservation" {{ $type == 'reservation' ? 'selected' : '' }}>予約</option>
                            </select>
                        </div>
                        
                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                検索
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($keyword)
            <div class="mb-4">
                <p class="text-gray-600">
                    「{{ $keyword }}」の検索結果
                </p>
            </div>
            @endif

            <!-- ホテル検索結果 -->
            @if(isset($results['hotels']) && $results['hotels']->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">ホテル</h3>
                    <div class="space-y-4">
                        @foreach($results['hotels'] as $hotel)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <h4 class="font-semibold mb-2">
                                <a href="{{ route('hotels.show', $hotel) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $hotel->name }}
                                </a>
                            </h4>
                            @if($hotel->address)
                            <p class="text-sm text-gray-600">{{ $hotel->address }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- サービス検索結果 -->
            @if(isset($results['services']) && $results['services']->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">サービス</h3>
                    <div class="space-y-4">
                        @foreach($results['services'] as $service)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <h4 class="font-semibold mb-2">
                                <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $service->title }}
                                </a>
                            </h4>
                            @if($service->body)
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($service->body, 100) }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- 予約検索結果 -->
            @if(isset($results['reservations']) && $results['reservations']->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">予約</h3>
                    <div class="space-y-4">
                        @foreach($results['reservations'] as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <h4 class="font-semibold mb-2">
                                <a href="{{ route('reservation.show', $reservation) }}" class="text-blue-600 hover:text-blue-800">
                                    予約 #{{ $reservation->id }}
                                </a>
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}
                                ～
                                {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if((!isset($results['hotels']) || $results['hotels']->count() == 0) && 
                (!isset($results['services']) || $results['services']->count() == 0) && 
                (!isset($results['reservations']) || $results['reservations']->count() == 0))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    @if($keyword)
                        検索結果が見つかりませんでした。
                    @else
                        キーワードを入力して検索してください。
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

