<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カート
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

            <!-- 予約情報表示 -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-2">予約情報</h3>
                <p class="text-sm">
                    チェックイン: {{ \Carbon\Carbon::parse($reservation_data['checkin_date'])->format('Y年m月d日') }} ～
                    チェックアウト: {{ \Carbon\Carbon::parse($reservation_data['checkout_date'])->format('m月d日') }}
                    ({{ $reservation_data['days'] }}泊)
                </p>
                <p class="text-sm mt-1">
                    大人{{ $reservation_data['adult'] }}名
                    @if($reservation_data['child'] > 0) / 子供{{ $reservation_data['child'] }}名 @endif
                    @if($reservation_data['dog'] > 0) / 犬{{ $reservation_data['dog'] }}頭 @endif
                </p>
            </div>

            <!-- カート内容 -->
            @if($tmp_orders->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">カート内容</h3>
                    
                    <div class="space-y-4">
                        @foreach($tmp_orders as $tmp_order)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-lg">{{ $tmp_order->service->title ?? 'サービス' }}</h4>
                                    @if($tmp_order->serviceOption)
                                    <p class="text-sm text-gray-600 mt-1">{{ $tmp_order->serviceOption->title }}</p>
                                    @endif
                                    <div class="mt-2 text-sm text-gray-600">
                                        <p>単価: ¥{{ number_format($tmp_order->price) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <form method="POST" action="{{ route('reservation.cart_update', $tmp_order) }}" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center space-x-2 mb-2">
                                            <label for="quantity_{{ $tmp_order->id }}" class="text-sm text-gray-600">数量:</label>
                                            <input type="number" 
                                                   name="quantity" 
                                                   id="quantity_{{ $tmp_order->id }}"
                                                   value="{{ $tmp_order->quantity }}" 
                                                   min="{{ $tmp_order->service->minimum ?? 1 }}" 
                                                   max="{{ $tmp_order->service->stock > 0 ? $tmp_order->service->stock : 999 }}"
                                                   class="w-20 rounded-md border-gray-300 shadow-sm text-sm"
                                                   onchange="this.form.submit()">
                                            <span class="text-sm text-gray-500">{{ $tmp_order->service->unit ?? '個' }}</span>
                                        </div>
                                    </form>
                                    <p class="text-lg font-semibold mb-2">¥{{ number_format($tmp_order->total_price) }}</p>
                                    <form method="POST" action="{{ route('reservation.cart_delete', $tmp_order) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('カートから削除しますか？')"
                                                class="text-sm text-red-600 hover:text-red-800">
                                            削除
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-semibold">合計金額</span>
                            <span class="text-3xl font-bold text-blue-600">
                                ¥{{ number_format($total_price) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <p class="text-gray-500 py-8">カートに商品がありません</p>
                    <a href="{{ route('reservation.service') }}" 
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        サービスを選択する
                    </a>
                </div>
            </div>
            @endif

            <!-- ボタン -->
            <div class="flex items-center justify-between">
                <a href="{{ route('reservation.service') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    ← サービス選択に戻る
                </a>
                @if($tmp_orders->count() > 0)
                <a href="{{ route('reservation.confirm') }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    予約確認へ →
                </a>
                @else
                <button disabled 
                        class="px-6 py-2 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed">
                    予約確認へ →
                </button>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

