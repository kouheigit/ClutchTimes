<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            日付詳細: {{ $date->format('Y年m月d日') }}({{ $date->locale('ja')->isoFormat('ddd') }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('calendar.index', ['year' => $date->year, 'month' => $date->month]) }}" 
                   class="text-blue-600 hover:text-blue-800">
                    ← カレンダーに戻る
                </a>
            </div>

            <!-- カレンダー情報 -->
            @if($calendar)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FIXDAY情報</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">期間</span>
                            <span class="font-medium">
                                {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日') }}
                                ～
                                {{ \Carbon\Carbon::parse($calendar->end_date)->format('Y年m月d日') }}
                            </span>
                        </div>
                        @if($calendar->hotel)
                        <div class="flex justify-between">
                            <span class="text-gray-600">ホテル</span>
                            <span class="font-medium">{{ $calendar->hotel->name }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600">ステータス</span>
                            <span>
                                @if($calendar->status == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        予約可能
                                    </span>
                                @elseif($calendar->status == 2)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        予約中
                                    </span>
                                @elseif($calendar->status == 3)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        予約確定
                                    </span>
                                @endif
                            </span>
                        </div>
                        @if($calendar->status == 1)
                        <div class="mt-4">
                            <a href="{{ route('reservation.create', ['calendar_id' => $calendar->id]) }}" 
                               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                予約する
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- 予約情報 -->
            @if($reservations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">予約情報</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-medium text-lg">
                                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}
                                        ～
                                        {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y年m月d日') }}
                                        ({{ $reservation->days }}泊)
                                    </p>
                                    @if($reservation->hotel)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $reservation->hotel->name }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">
                                        大人{{ $reservation->adult }}名
                                        @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                                        @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
                                    </p>
                                    <p class="mt-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                            @if($reservation->status == \App\Consts\ReservationConst::STATUS_RESERVED) bg-blue-100 text-blue-800
                                            @elseif($reservation->status == \App\Consts\ReservationConst::STATUS_UNDER_RESERVATION) bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ \App\Consts\ReservationConst::STATUS_LIST[$reservation->status] ?? '不明' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('reservation.show', $reservation) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        詳細を見る
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if(!$calendar && $reservations->count() == 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <p class="text-gray-500 py-8">この日の情報はありません</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

