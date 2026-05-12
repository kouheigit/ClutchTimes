<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            トップページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <style>
                .top-grid-container {
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                }
                @media screen and (min-width: 1024px) {
                    .top-grid-container {
                        display: grid !important;
                        grid-template-columns: 1fr 2fr !important;
                        gap: 1.5rem !important;
                    }
                }
                .btn-small {
                    padding: 0.375rem 0.75rem !important;
                    font-size: 0.75rem !important;
                    line-height: 1.25rem !important;
                }
            </style>
            <!-- グリッドレイアウト: 左側（天気・交通情報）、右側（ポイント・予約など） -->
            <div class="top-grid-container">
                <!-- 左カラム: 天気・交通情報 -->
                <div class="space-y-6" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- 天気情報 -->
                    @if($today_weather)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold mb-4">今日の天気（軽井沢）</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    @if(isset($today_weather['weather_icon']))
                                    <!-- 天気アイコンは非表示 -->
                                    @endif
                                    <div>
                                        <p class="text-3xl font-bold">{{ $today_weather['temp'] }}°C</p>
                                        <p class="text-lg">{{ $today_weather['weather'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $today_weather['description'] }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pt-2 border-t">
                                    <div>
                                        <p class="text-sm text-gray-600">最高気温</p>
                                        <p class="text-xl font-semibold">{{ $today_weather['temp_max'] }}°C</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">最低気温</p>
                                        <p class="text-xl font-semibold">{{ $today_weather['temp_min'] }}°C</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">湿度</p>
                                        <p class="text-xl font-semibold">{{ $today_weather['humidity'] }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">風速</p>
                                        <p class="text-xl font-semibold">{{ $today_weather['wind_speed'] }}m/s</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- 5日間予報 -->
                    @if(isset($forecast) && count($forecast) > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">5日間予報</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        @foreach($forecast as $day)
                        <div class="text-center">
                            <p class="text-sm font-semibold mb-2">{{ $day['date_formatted'] }}</p>
                            @if(isset($day['weather_icon']))
                            <!-- 天気アイコンは非表示 -->
                            @endif
                            <p class="text-lg font-bold mt-2">{{ $day['temp'] }}°C</p>
                            <p class="text-xs text-gray-600">{{ $day['temp_max'] }}° / {{ $day['temp_min'] }}°</p>
                            <p class="text-xs text-gray-500 mt-1">降水確率: {{ $day['pop'] }}%</p>
                        </div>
                        @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- 交通情報 -->
                    @if($traffic_info)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">交通情報</h3>
                    <div class="space-y-2">
                        <p><span class="font-semibold">出発地:</span> {{ $traffic_info['start_address'] }}</p>
                        <p><span class="font-semibold">目的地:</span> {{ $traffic_info['end_address'] }}</p>
                        <p><span class="font-semibold">距離:</span> {{ $traffic_info['distance'] }}</p>
                        <p><span class="font-semibold">所要時間:</span> {{ $traffic_info['duration'] }}</p>
                        @if(isset($traffic_info['duration_in_traffic']))
                        <p><span class="font-semibold">渋滞込み:</span> {{ $traffic_info['duration_in_traffic'] }}</p>
                        @endif
                        @if(isset($traffic_info['traffic_status']))
                        <p>
                            <span class="font-semibold">渋滞状況:</span>
                            <span class="px-2 py-1 rounded text-sm" style="background-color: {{ $traffic_info['traffic_status']['color'] }}; color: white;">
                                {{ $traffic_info['traffic_status']['text'] }}
                            </span>
                        </p>
                        @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- 右カラム: ポイント・予約・お知らせなど -->
                <div class="space-y-6" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- ポイント残高 -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">保有ポイント</h3>
                    <p class="text-4xl font-bold">{{ number_format($pointbalance) }}P</p>
                    <a href="{{ route('mypage.pointlog') }}" class="inline-block mt-4 text-sm underline hover:text-blue-100">
                        ポイント履歴を見る →
                    </a>
                </div>
            </div>

                    <!-- 今後の予約 -->
                    @if($reservations->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">今後の予約</h3>
                        <a href="{{ route('reservation.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            全て見る →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-medium text-lg">
                                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($reservation->checkin_date)->locale('ja')->isoFormat('ddd') }})
                                        ～ {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($reservation->checkout_date)->locale('ja')->isoFormat('ddd') }})
                                        ({{ $reservation->days }}泊)
                                    </p>
                                    @if($reservation->hotel)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $reservation->hotel->name }}
                                    </p>
                                    @endif
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
                                       class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-gray-700 btn-small">
                                        詳細
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

                    <!-- お知らせ -->
                    @if($info)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">お知らせ</h3>
                        <a href="{{ route('information.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            全て見る →
                        </a>
                    </div>
                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                        <p class="font-semibold text-lg">{{ $info->title }}</p>
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($info->body, 150) }}</p>
                        <div class="mt-4">
                            <a href="{{ route('information.show', $info) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                続きを読む →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

                    <!-- FIXDAY（固定日予約） -->
                    @if($calendars->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">FIXDAY（固定日予約）</h3>
                        <a href="{{ route('reservation.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            全て見る →
                        </a>
                    </div>
                    <div class="space-y-4">
                        @foreach($calendars->take(5) as $calendar)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="font-semibold text-lg">
                                        {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($calendar->start_date)->locale('ja')->isoFormat('ddd') }})
                                        ～ {{ \Carbon\Carbon::parse($calendar->end_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($calendar->end_date)->locale('ja')->isoFormat('ddd') }})
                                    </p>
                                    @if($calendar->hotel)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $calendar->hotel->name }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-600 mt-1">
                                        宿泊日数: {{ \Carbon\Carbon::parse($calendar->start_date)->diffInDays($calendar->end_date) }}泊
                                    </p>
                                    <p class="mt-2">
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
                                    </p>
                                </div>
                                <div>
                                    @if($calendar->status == 1)
                                        <a href="{{ route('reservation.create', ['calendar_id' => $calendar->id]) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-blue-700 btn-small">
                                            予約する
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

                    <!-- FREEDAY -->
                    @if($freedays->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">FREEDAY（フリーデイ）</h3>
                                <a href="{{ route('reservation.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    全て見る →
                                </a>
                            </div>
                            <div class="space-y-4">
                                @foreach($freedays->take(5) as $freeday)
                                @php
                                    $availableFrom = \Carbon\Carbon::parse($freeday->start_date)->firstOfMonth()->subMonths(18);
                                    $canUse = \Carbon\Carbon::now()->gte($availableFrom) && \Carbon\Carbon::parse($freeday->end_date)->isFuture() && $freeday->freedays > 0;
                                @endphp
                                <div class="border rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <p class="font-semibold text-lg">
                                                利用可能: <span class="text-blue-600 font-bold">{{ $freeday->freedays }}泊</span>
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                有効期限: {{ \Carbon\Carbon::parse($freeday->end_date)->format('Y年m月末日') }}まで
                                            </p>
                                            @if(!$canUse)
                                            <p class="text-xs text-gray-500 mt-1">
                                                利用開始: {{ $availableFrom->format('Y年m月') }}から
                                            </p>
                                            @endif
                                        </div>
                                        <div>
                                            @if($canUse)
                                                <a href="{{ route('reservation.create', ['fr' => $freeday->id]) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-green-700 btn-small">
                                                    予約する
                                                </a>
                                            @else
                                                <button disabled 
                                                        class="inline-flex items-center px-3 py-1.5 bg-gray-300 border border-transparent rounded-md font-medium text-xs text-white cursor-not-allowed btn-small">
                                                    利用不可
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

