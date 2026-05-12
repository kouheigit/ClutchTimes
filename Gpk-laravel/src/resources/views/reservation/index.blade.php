<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約管理
        </h2>
            <a href="{{ route('calendar.index') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                カレンダー表示
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- FIXDAY -->
            @if($calendars->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FIXDAY（固定日予約）</h3>
                    
                    <div class="space-y-4">
                        @foreach($calendars as $calendar)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-lg font-medium">
                                        {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($calendar->start_date)->locale('ja')->isoFormat('ddd') }})
                                        ～
                                        {{ \Carbon\Carbon::parse($calendar->end_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($calendar->end_date)->locale('ja')->isoFormat('ddd') }})
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
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FREEDAY（自由日予約）</h3>
                    
                    <div class="space-y-4">
                        @foreach($freedays as $freeday)
                        @php
                            $availableFrom = \Carbon\Carbon::parse($freeday->start_date)->firstOfMonth()->subMonths(18);
                            $canUse = \Carbon\Carbon::now()->gte($availableFrom) && \Carbon\Carbon::parse($freeday->end_date)->isFuture() && $freeday->freedays > 0;
                        @endphp
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="text-lg font-medium">
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
                                       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                        予約する
                                    </a>
                                    @else
                                        <button disabled 
                                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
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

            <!-- 予約一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">予約一覧</h3>
                        
                        <!-- フィルター -->
                        <div class="flex space-x-2">
                            <a href="{{ route('reservation.index', ['status' => 'active']) }}" 
                               class="px-3 py-1 text-sm rounded {{ ($status_filter ?? 'active') == 'active' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                今後の予約
                            </a>
                            <a href="{{ route('reservation.index', ['status' => 'past']) }}" 
                               class="px-3 py-1 text-sm rounded {{ ($status_filter ?? 'active') == 'past' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                過去の予約
                            </a>
                            <a href="{{ route('reservation.index', ['status' => 'all']) }}" 
                               class="px-3 py-1 text-sm rounded {{ ($status_filter ?? 'active') == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                全て
                            </a>
                        </div>
                    </div>
                    
                    @if($reservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <div class="flex-1">
                                    <p class="text-lg font-medium">
                                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($reservation->checkin_date)->locale('ja')->isoFormat('ddd') }})
                                        ～ {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($reservation->checkout_date)->locale('ja')->isoFormat('ddd') }})
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
                                    @if($reservation->orders->count() > 0 || $reservation->addOrders->count() > 0)
                                    <p class="text-sm font-semibold text-blue-600 mt-1">
                                        合計: ¥{{ number_format($reservation->orders->sum('total_price') + $reservation->addOrders->sum('total_price')) }}
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
                                <div class="flex flex-col items-end space-y-2">
                                    <a href="{{ route('reservation.show', $reservation) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        詳細を見る
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">予約がありません</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

