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

            @if($cart && $cart->cartDetails->count() > 0)
            <!-- カート内容 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">カート内容</h3>
                    
                    <div class="space-y-4">
                        @foreach($cart->cartDetails as $detail)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-lg">{{ $detail->service->title ?? 'サービス' }}</h4>
                                    @if($detail->serviceOption)
                                    <p class="text-sm text-gray-600 mt-1">{{ $detail->serviceOption->title }}</p>
                                    @endif
                                    <div class="mt-2 text-sm text-gray-600">
                                        <form method="POST" action="{{ route('cart.update', $detail) }}" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center space-x-2 mb-2">
                                                <label for="quantity_{{ $detail->id }}" class="text-sm text-gray-600">数量:</label>
                                                <input type="number" 
                                                       name="quantity" 
                                                       id="quantity_{{ $detail->id }}"
                                                       value="{{ $detail->quantity }}" 
                                                       min="{{ $detail->service->minimum ?? 1 }}" 
                                                       max="{{ $detail->service->stock > 0 ? $detail->service->stock : 999 }}"
                                                       class="w-20 rounded-md border-gray-300 shadow-sm text-sm"
                                                       onchange="this.form.submit()">
                                                <span class="text-sm text-gray-500">{{ $detail->service->unit ?? '個' }}</span>
                                            </div>
                                        </form>
                                        <p>単価: ¥{{ number_format($detail->price) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-semibold">¥{{ number_format($detail->total_price) }}</p>
                                    <form method="POST" action="{{ route('cart.delete', $detail) }}" class="mt-2">
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

            <!-- 予約選択（任意） -->
            @if($last_reservation)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold mb-2">予約に関連付ける（任意）</h3>
                <div class="space-y-2">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">予約ID:</span> {{ $last_reservation->id }}
                    </p>
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">期間:</span> 
                        {{ \Carbon\Carbon::parse($last_reservation->checkin_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($last_reservation->checkin_date)->locale('ja')->isoFormat('ddd') }})
                        ～ {{ \Carbon\Carbon::parse($last_reservation->checkout_date)->format('m月d日') }}({{ \Carbon\Carbon::parse($last_reservation->checkout_date)->locale('ja')->isoFormat('ddd') }})
                        ({{ $last_reservation->days }}泊)
                    </p>
                    @if($last_reservation->hotel)
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">ホテル:</span> {{ $last_reservation->hotel->name }}
                    </p>
                    @endif
                    <p class="text-xs text-gray-600 mt-2">
                        注文確認画面でこの予約に関連付けることができます
                    </p>
                </div>
            </div>
            @endif

            <!-- ボタン -->
            <div class="flex items-center justify-between">
                <a href="{{ route('services.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    ← サービス一覧に戻る
                </a>
                <a href="{{ route('cart.confirm', $cart) }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    注文確認へ →
                </a>
            </div>
            @else
            <!-- 空のカート -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <p class="text-gray-500 py-8">カートに商品がありません</p>
                    <a href="{{ route('services.index') }}" 
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        サービスを選択する
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

