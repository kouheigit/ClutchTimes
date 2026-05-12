@props(['orders', 'showDetails' => true])

<div class="bg-white border rounded-lg p-4">
    <h4 class="text-lg font-semibold mb-3">注文内容</h4>
    @if($orders->count() > 0)
    <div class="space-y-3">
        @foreach($orders as $order)
        <div class="border-b pb-3 last:border-b-0">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <p class="font-medium">{{ $order->service->title ?? 'サービス' }}</p>
                    @if($showDetails && $order->orderDetails)
                        @foreach($order->orderDetails as $detail)
                        <div class="mt-2 text-sm text-gray-600">
                            @if($detail->serviceOption)
                            <p class="text-xs text-gray-500">
                                <span class="inline-block bg-gray-100 px-2 py-1 rounded">{{ $detail->serviceOption->title }}</span>
                            </p>
                            @endif
                            <p>数量: {{ $detail->quantity }} {{ $order->service->unit ?? '' }}</p>
                            <p>単価: ¥{{ number_format($detail->price) }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="text-right">
                    <p class="font-semibold">¥{{ number_format($order->total_price) }}</p>
                    <x-status-badge :status="$order->payment_status" type="payment" />
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4 pt-4 border-t">
        <div class="flex justify-between items-center">
            <span class="text-lg font-semibold">合計</span>
            <span class="text-2xl font-bold text-blue-600">
                ¥{{ number_format($orders->sum('total_price')) }}
            </span>
        </div>
    </div>
    @else
    <p class="text-gray-500 text-center py-4">注文がありません</p>
    @endif
</div>




















