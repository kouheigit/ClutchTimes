<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                注文明細詳細
            </h2>
            <a href="{{ route('order-details.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($orderDetail->order)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">注文</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="{{ route('orders.show', $orderDetail->order) }}" class="text-blue-600 hover:text-blue-800">
                                    #{{ $orderDetail->order->id }}
                                </a>
                            </dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">サービス</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($orderDetail->service)
                                    {{ $orderDetail->service->title }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">オプション</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($orderDetail->serviceOption)
                                    {{ $orderDetail->serviceOption->title }}
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">数量</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $orderDetail->quantity }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">単価</dt>
                            <dd class="mt-1 text-sm text-gray-900">¥{{ number_format($orderDetail->price) }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">小計</dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                ¥{{ number_format($orderDetail->price * $orderDetail->quantity) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

