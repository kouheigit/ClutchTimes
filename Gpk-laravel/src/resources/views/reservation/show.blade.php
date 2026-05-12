<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">予約情報</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">予約ID</p>
                            <p class="text-lg font-medium">{{ $reservation->id }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">ステータス</p>
                            <p>
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
                            <p class="text-sm text-gray-600">チェックイン</p>
                            <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">チェックアウト</p>
                            <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y年m月d日') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">宿泊日数</p>
                            <p class="text-lg font-medium">{{ $reservation->days }}泊</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">宿泊人数</p>
                            <p class="text-lg font-medium">
                                大人{{ $reservation->adult }}名
                                @if($reservation->child > 0) / 子供{{ $reservation->child }}名 @endif
                                @if($reservation->dog > 0) / 犬{{ $reservation->dog }}頭 @endif
                            </p>
                        </div>
                        
                        @if($reservation->hotel)
                        <div>
                            <p class="text-sm text-gray-600">ホテル</p>
                            <p class="text-lg font-medium">{{ $reservation->hotel->name }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-sm text-gray-600">決済方法</p>
                            <p class="text-lg font-medium">
                                {{ $reservation->payment == 0 ? '現地払い' : 'クレジットカード' }}
                            </p>
                        </div>
                        
                        @if($reservation->user)
                        <div>
                            <p class="text-sm text-gray-600">予約者</p>
                            <p class="text-lg font-medium">{{ $reservation->user->name }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-sm text-gray-600">予約日時</p>
                            <p class="text-lg font-medium">{{ \Carbon\Carbon::parse($reservation->created_at)->format('Y年m月d日 H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($reservation->note)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">備考</p>
                        <p class="text-lg">{{ $reservation->note }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- 注文情報 -->
            @if($reservation->orders->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">サービス注文（事前予約）</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservation->orders as $order)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-medium text-lg">{{ $order->service->title ?? 'サービス' }}</p>
                                    @if($order->orderDetails->count() > 0)
                                        @foreach($order->orderDetails as $detail)
                                            @if($detail->serviceOption)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <span class="inline-block bg-gray-100 px-2 py-1 rounded">
                                                    {{ $detail->serviceOption->title }}
                                                    @if($detail->serviceOption->price > 0)
                                                        (+¥{{ number_format($detail->serviceOption->price) }})
                                                    @endif
                                                </span>
                                            </p>
                                            @endif
                                        @endforeach
                                    @endif
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p>数量: {{ $order->quantity }}{{ $order->service->unit ?? '個' }}</p>
                                        <p>単価: ¥{{ number_format($order->price) }}</p>
                                        @if($order->orderDetails->count() > 0)
                                            @foreach($order->orderDetails as $detail)
                                                @if($detail->serviceOption)
                                                <p class="text-xs text-gray-500">
                                                    オプション: {{ $detail->serviceOption->title }}
                                                </p>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold">¥{{ number_format($order->total_price) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-semibold">事前予約合計</span>
                            <span class="text-2xl font-bold text-blue-600">
                                ¥{{ number_format($reservation->orders->sum('total_price')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- 追加注文情報 -->
            @if($reservation->addOrders->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">追加注文</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservation->addOrders as $addOrder)
                        <div class="border rounded-lg p-4">
                            <div class="mb-3 pb-3 border-b">
                                <p class="text-sm text-gray-600">注文日時: {{ \Carbon\Carbon::parse($addOrder->created_at)->format('Y年m月d日 H:i') }}</p>
                                <p class="text-sm text-gray-600">決済方法: {{ $addOrder->payment == 0 ? '現地払い' : 'クレジットカード' }}</p>
                            </div>
                            
                            @if($addOrder->addOrderDetails->count() > 0)
                            <div class="space-y-2">
                                @foreach($addOrder->addOrderDetails as $detail)
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="font-medium">{{ $detail->service->title ?? 'サービス' }}</p>
                                        @if($detail->serviceOption)
                                        <p class="text-sm text-gray-500 mt-1">
                                            <span class="inline-block bg-gray-100 px-2 py-1 rounded">{{ $detail->serviceOption->title }}</span>
                                        </p>
                                        @endif
                                        <p class="text-sm text-gray-600 mt-1">
                                            数量: {{ $detail->quantity }} × ¥{{ number_format($detail->price) }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold">¥{{ number_format($detail->total_price) }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            <div class="mt-4 pt-4 border-t">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">小計</span>
                                    <span class="text-lg font-bold">¥{{ number_format($addOrder->total_price) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-semibold">追加注文合計</span>
                            <span class="text-2xl font-bold text-green-600">
                                ¥{{ number_format($reservation->addOrders->sum('total_price')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- 総合計 -->
            @if($reservation->orders->count() > 0 || $reservation->addOrders->count() > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <div class="space-y-2">
                    @if($reservation->orders->count() > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-700">事前予約合計</span>
                        <span class="text-xl font-semibold text-gray-700">
                            ¥{{ number_format($reservation->orders->sum('total_price')) }}
                        </span>
                    </div>
                    @endif
                    @if($reservation->addOrders->count() > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-700">追加注文合計</span>
                        <span class="text-xl font-semibold text-gray-700">
                            ¥{{ number_format($reservation->addOrders->sum('total_price')) }}
                        </span>
                    </div>
                    @endif
                    <div class="pt-2 border-t border-blue-300">
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-semibold">総合計</span>
                            <span class="text-4xl font-bold text-blue-600">
                                ¥{{ number_format($reservation->orders->sum('total_price') + $reservation->addOrders->sum('total_price')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- アクション -->
            <div class="flex items-center justify-end">
                <a href="{{ route('reservation.index') }}" 
                   class="mr-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    戻る
                </a>
                @if($reservation->status != \App\Consts\ReservationConst::STATUS_CANCEL)
                <a href="{{ route('reservation.edit', $reservation) }}" 
                   class="mr-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    編集
                </a>
                @endif
                @if(Auth::user()->type == \App\Consts\UserConst::TYPE_OWNER && $reservation->status != \App\Consts\ReservationConst::STATUS_CANCEL)
                <a href="{{ route('invitation.create', ['reservation_id' => $reservation->id]) }}" 
                   class="mr-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    ゲストを招待
                </a>
                @endif
                @if($reservation->status != \App\Consts\ReservationConst::STATUS_CANCEL)
                <form method="POST" action="{{ route('reservation.cancel', $reservation) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('予約をキャンセルしますか？')"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        キャンセル
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

