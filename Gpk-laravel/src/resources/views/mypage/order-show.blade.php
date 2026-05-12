<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            注文詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('mypage.orders') }}" class="text-blue-600 hover:text-blue-800">
                    ← 注文一覧に戻る
                </a>
            </div>

            <!-- 注文情報 -->
            <x-card title="注文情報">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">注文ID:</span>
                        <span class="font-medium">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">注文日:</span>
                        <span class="font-medium">{{ $order->created_at->format('Y年m月d日 H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">サービス:</span>
                        <span class="font-medium">{{ $order->service->title ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">数量:</span>
                        <span class="font-medium">{{ $order->quantity }}{{ $order->service->unit ?? '個' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">単価:</span>
                        <span class="font-medium">¥{{ number_format($order->price) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">合計金額:</span>
                        <span class="font-medium text-lg text-blue-600">¥{{ number_format($order->total_price) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">支払い方法:</span>
                        <span class="font-medium">{{ $order->payment == 1 ? 'クレジットカード' : '現地払い' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">支払いステータス:</span>
                        <x-status-badge :status="$order->payment_status" type="payment" />
                    </div>
                    @if($order->reservation)
                    <div class="flex justify-between">
                        <span class="text-gray-600">関連予約:</span>
                        <a href="{{ route('reservation.show', $order->reservation) }}" class="font-medium text-blue-600 hover:text-blue-800">
                            予約ID: {{ $order->reservation->id }}
                        </a>
                    </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>




















