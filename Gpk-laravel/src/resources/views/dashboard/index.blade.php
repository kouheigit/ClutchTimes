<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ポイント残高 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">ポイント残高</h3>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ number_format($user_point) }}ポイント
                    </p>
                </div>
            </div>

            <!-- 今後の予約 -->
            @if($upcoming_reservations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">今後の予約</h3>
                        <a href="{{ route('reservation.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            すべて見る →
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($upcoming_reservations as $reservation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}
                                        ～
                                        {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('m月d日') }}
                                    </p>
                                    @if($reservation->hotel)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $reservation->hotel->name }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-1">
                                        ステータス: 
                                        @if($reservation->status == 1)
                                            <span class="text-green-600">予約済み</span>
                                        @elseif($reservation->status == 2)
                                            <span class="text-yellow-600">確認中</span>
                                        @elseif($reservation->status == 3)
                                            <span class="text-blue-600">確定</span>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('reservation.show', $reservation) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    詳細
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- 最近の注文 -->
            @if($recent_orders->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">最近の注文</h3>
                        <a href="{{ route('orders.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            すべて見る →
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($recent_orders as $order)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold">
                                        注文 #{{ $order->id }}
                                    </p>
                                    @if($order->service)
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $order->service->title }}
                                    </p>
                                    @endif
                                    <p class="text-sm text-gray-500 mt-1">
                                        ¥{{ number_format($order->price) }} - 
                                        {{ $order->created_at->format('Y/m/d H:i') }}
                                    </p>
                                </div>
                                <a href="{{ route('orders.show', $order) }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    詳細
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

