<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <!-- ポイント残高 -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">保有ポイント</h3>
                    <p class="text-4xl font-bold">{{ number_format($user_point ?? 0) }} P</p>
                    
                    @if($pointbalance->count() > 0)
                    <div class="mt-4 space-y-2">
                        @foreach($pointbalance as $balance)
                        <div class="text-sm">
                            <span>{{ $balance->point }}P</span>
                            <span class="ml-2 opacity-80">
                                ({{ \Carbon\Carbon::parse($balance->to)->format('Y年m月末') }}まで有効)
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <a href="{{ route('mypage.pointlog') }}" class="inline-block mt-4 text-sm underline hover:text-blue-100">
                        ポイント履歴を見る →
                    </a>
                </div>
            </div>

            <!-- FREEDAY -->
            @if(Auth::user()->type == \App\Consts\UserConst::TYPE_OWNER && $freedays->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">FREEDAY（利用可能日数）</h3>
                    
                    <div class="space-y-3">
                        @foreach($freedays as $freeday)
                        <div class="flex justify-between items-center border-b pb-3">
                            <div>
                                <p class="font-medium">{{ $freeday->freedays }}泊</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($freeday->end_date)->format('Y年m月末日') }}まで有効
                                </p>
                            </div>
                            <a href="{{ route('reservation.create', ['fr' => $freeday->id]) }}" 
                               class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                予約する
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- 今後の予約 -->
            @if($reservations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">今後の予約</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">
                                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}
                                        ～
                                        {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}
                                        ({{ $reservation->days }}泊)
                                    </p>
                                    @if($reservation->hotel)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $reservation->hotel->name }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-600">
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
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        詳細 →
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('mypage.history') }}" class="text-blue-600 hover:text-blue-800">
                            すべての予約履歴を見る →
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-gray-600">今後の予約はありません</p>
                    <a href="{{ route('reservation.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                        予約する →
                    </a>
                </div>
            </div>
            @endif

            <!-- プロフィール編集 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">アカウント設定</h3>
                    
                    <div class="space-y-2">
                        <a href="{{ route('mypage.edit') }}" class="block p-3 hover:bg-gray-50 rounded transition">
                            <div class="flex justify-between items-center">
                                <span>プロフィール編集</span>
                                <span>→</span>
                            </div>
                        </a>
                        <a href="{{ route('mypage.history') }}" class="block p-3 hover:bg-gray-50 rounded transition">
                            <div class="flex justify-between items-center">
                                <span>予約履歴</span>
                                <span>→</span>
                            </div>
                        </a>
                        <a href="{{ route('mypage.pointlog') }}" class="block p-3 hover:bg-gray-50 rounded transition">
                            <div class="flex justify-between items-center">
                                <span>ポイント履歴</span>
                                <span>→</span>
                            </div>
                        </a>
                        <a href="{{ route('mypage.orders') }}" class="block p-3 hover:bg-gray-50 rounded transition">
                            <div class="flex justify-between items-center">
                                <span>注文一覧</span>
                                <span>→</span>
                            </div>
                        </a>
                        <a href="{{ route('mypage.reservations') }}" class="block p-3 hover:bg-gray-50 rounded transition">
                            <div class="flex justify-between items-center">
                                <span>予約一覧</span>
                                <span>→</span>
                            </div>
                        </a>
                        @if(Auth::user()->type == \App\Consts\UserConst::TYPE_OWNER)
                        <a href="{{ route('invitation.index') }}" class="block p-3 hover:bg-gray-50 rounded transition">
                            <div class="flex justify-between items-center">
                                <span>招待管理</span>
                                <span>→</span>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

