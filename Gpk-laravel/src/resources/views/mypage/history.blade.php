<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約履歴
        </h2>
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

            <div class="mb-4">
                <a href="{{ route('mypage.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← マイページに戻る
                </a>
            </div>

            @if($reservations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">予約履歴一覧</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservations as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">予約日</p>
                                            <p class="font-medium">
                                                {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($reservation->checkin_date)->locale('ja')->isoFormat('ddd') }})
                                                ～
                                                {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($reservation->checkout_date)->locale('ja')->isoFormat('ddd') }})
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                ({{ $reservation->days }}泊)
                                            </p>
                                        </div>
                                        
                                        <div>
                                            <p class="text-sm text-gray-600">ステータス</p>
                                            <p class="mt-1">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                    @if($reservation->status == \App\Consts\ReservationConst::STATUS_RESERVED) bg-blue-100 text-blue-800
                                                    @elseif($reservation->status == \App\Consts\ReservationConst::STATUS_UNDER_RESERVATION) bg-yellow-100 text-yellow-800
                                                    @elseif($reservation->status == \App\Consts\ReservationConst::STATUS_CANCEL) bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ \App\Consts\ReservationConst::STATUS_LIST[$reservation->status] ?? '不明' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($reservation->hotel)
                                    <p class="text-sm text-gray-600 mt-2">
                                        ホテル: {{ $reservation->hotel->name }}
                                    </p>
                                    @endif
                                    
                                    <p class="text-sm text-gray-600 mt-1">
                                        大人{{ $reservation->adult }}名
                                        @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                                        @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
                                    </p>
                                    
                                    @if($reservation->orders->count() > 0 || $reservation->addOrders->count() > 0)
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600">サービス注文:</p>
                                        <ul class="text-sm text-gray-600 list-disc list-inside">
                                            @foreach($reservation->orders as $order)
                                            <li>{{ $order->service->title ?? 'サービス' }} × {{ $order->quantity }}</li>
                                            @endforeach
                                        </ul>
                                        @if($reservation->addOrders->count() > 0)
                                        <p class="text-sm text-gray-500 mt-1">追加注文: {{ $reservation->addOrders->count() }}件</p>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-blue-600 mt-2">
                                        合計: ¥{{ number_format($reservation->orders->sum('total_price') + $reservation->addOrders->sum('total_price')) }}
                                    </p>
                                    @endif
                                </div>
                                
                                <div class="ml-4 flex flex-col items-end space-y-2">
                                    <a href="{{ route('reservation.show', $reservation) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                        詳細を見る
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- ページネーション -->
                    <div class="mt-6">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-gray-600">予約履歴がありません</p>
                    <a href="{{ route('reservation.index') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                        予約する →
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

