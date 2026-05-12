<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                注文詳細 #{{ $order->id }}
            </h2>
            <a href="{{ route('orders.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">注文情報</h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">注文ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $order->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
                            <dd class="mt-1">
                                @if($order->status == 1)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        注文済み
                                    </span>
                                @elseif($order->status == 2)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        キャンセル
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        その他
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">タイプ</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($order->type == 1)
                                    予約時
                                @elseif($order->type == 2)
                                    現地
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">注文日時</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->created_at->format('Y年m月d日 H:i') }}
                            </dd>
                        </div>
                        
                        @if($order->service)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">サービス</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('services.show', $order->service) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $order->service->title }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        
                        @if($order->reservation)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">予約</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('reservation.show', $order->reservation) }}" class="text-blue-600 hover:text-blue-800">
                                    予約 #{{ $order->reservation->id }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">金額</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                ¥{{ number_format($order->price) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            @if($order->orderDetails->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">注文明細</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        サービス
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        オプション
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        数量
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        単価
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        小計
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($detail->service)
                                            {{ $detail->service->title }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($detail->serviceOption)
                                            {{ $detail->serviceOption->title }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $detail->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ¥{{ number_format($detail->price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ¥{{ number_format($detail->price * $detail->quantity) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

