<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            注文一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <x-alert type="success" dismissible>
                {{ session('success') }}
            </x-alert>
            @endif

            <!-- 注文一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($orders->count() > 0)
                    <x-table :headers="['注文ID', 'サービス', '数量', '金額', 'ステータス', '日付', '操作']">
                        @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->service->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->quantity }}{{ $order->service->unit ?? '個' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ¥{{ number_format($order->total_price) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$order->payment_status" type="payment" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('Y年m月d日') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('mypage.order.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                    詳細
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </x-table>
                    
                    <div class="mt-6">
                        {{ $orders->links('components.pagination') }}
                    </div>
                    @else
                    <x-empty-state 
                        title="注文がありません"
                        description="まだ注文がありません。"
                        :action="route('services.index')"
                        actionLabel="サービスを見る" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




















