<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            サービス選択
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

            <!-- サービス一覧 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">事前予約可能なサービス</h3>
                    
                    @if($services->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($services as $service)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <h4 class="text-lg font-semibold mb-2">{{ $service->title }}</h4>
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($service->body, 100) }}</p>
                            
                            <div class="mb-4">
                                <span class="text-2xl font-bold text-blue-600">
                                    ¥{{ number_format($service->price) }}
                                </span>
                                <span class="text-sm text-gray-500">/ {{ $service->unit ?? '1個' }}</span>
                            </div>
                            
                            @if($service->stock > 0)
                            <p class="text-xs text-gray-500 mb-4">在庫: {{ $service->stock }}{{ $service->unit ?? '個' }}</p>
                            @endif
                            
                            <!-- サービスオプション -->
                            @if($service->serviceOptions->count() > 0)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">オプション</label>
                                @foreach($service->serviceOptions as $option)
                                <div class="flex items-center mb-2">
                                    <input type="radio" name="service_option_{{ $service->id }}" 
                                           value="{{ $option->id }}" 
                                           id="option_{{ $option->id }}"
                                           class="mr-2">
                                    <label for="option_{{ $option->id }}" class="text-sm">
                                        {{ $option->title }} (+¥{{ number_format($option->price) }})
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            <form method="POST" action="{{ route('reservation.cart_add') }}" class="mt-4" id="form_{{ $service->id }}">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $service->id }}">
                                <input type="hidden" name="service_option_id" id="service_option_{{ $service->id }}" value="">
                                <div class="flex items-center space-x-2 mb-2">
                                    <label class="text-sm">
                                        数量:
                                        @if($service->minimum > 1)
                                        <span class="text-xs text-red-500">(最小: {{ $service->minimum }}{{ $service->unit ?? '個' }})</span>
                                        @endif
                                    </label>
                                    <input type="number" name="quantity" 
                                           value="{{ max(1, $service->minimum ?? 1) }}" 
                                           min="{{ $service->minimum ?? 1 }}" 
                                           max="{{ $service->stock > 0 ? $service->stock : 999 }}" 
                                           required
                                           class="w-20 rounded-md border-gray-300 shadow-sm">
                                    <span class="text-sm text-gray-500">{{ $service->unit ?? '個' }}</span>
                                </div>
                                <button type="submit" 
                                        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                                    カートに追加
                                </button>
                            </form>
                            
                            @if($service->serviceOptions->count() > 0)
                            <script>
                                (function() {
                                    const form = document.getElementById('form_{{ $service->id }}');
                                    const optionInput = document.getElementById('service_option_{{ $service->id }}');
                                    
                                    if (form && optionInput) {
                                        form.addEventListener('submit', function(e) {
                                            // 選択されたオプションを取得
                                            const selectedRadio = form.querySelector('input[type="radio"][name="service_option_{{ $service->id }}"]:checked');
                                            if (selectedRadio) {
                                                optionInput.value = selectedRadio.value;
                                            }
                                        });
                                    }
                                })();
                            </script>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">事前予約可能なサービスはありません</p>
                    @endif
                </div>
            </div>

            <!-- カート内容 -->
            @if($tmp_orders->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">カート内容</h3>
                    
                    <div class="space-y-4">
                        @foreach($tmp_orders as $tmp_order)
                        <div class="flex justify-between items-center border-b pb-4">
                            <div>
                                <p class="font-medium">{{ $tmp_order->service->title ?? 'サービス' }}</p>
                                @if($tmp_order->serviceOption)
                                <p class="text-sm text-gray-600">{{ $tmp_order->serviceOption->title }}</p>
                                @endif
                                <p class="text-sm text-gray-600">
                                    数量: {{ $tmp_order->quantity }} / 
                                    単価: ¥{{ number_format($tmp_order->price) }} / 
                                    小計: ¥{{ number_format($tmp_order->total_price) }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('reservation.cart_delete', $tmp_order) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('カートから削除しますか？')"
                                        class="text-red-600 hover:text-red-800">
                                    削除
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">合計</span>
                            <span class="text-2xl font-bold text-blue-600">
                                ¥{{ number_format($tmp_orders->sum('total_price')) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- ボタン -->
            <div class="flex items-center justify-between">
                <a href="{{ route('reservation.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                    ← 戻る
                </a>
                @if($tmp_orders->count() > 0)
                <a href="{{ route('reservation.cart') }}" 
                   class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    カートを確認する →
                </a>
                @else
                <button disabled 
                        class="px-6 py-2 bg-gray-300 text-gray-500 rounded-md cursor-not-allowed">
                    カートを確認する →
                </button>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

