<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            サービス詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← サービス一覧に戻る
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif
                    
                    <h2 class="text-2xl font-bold mb-4">{{ $service->title }}</h2>
                    
                    @if($service->body)
                    <div class="prose mb-6">
                        <p class="text-gray-700 whitespace-pre-line">{{ $service->body }}</p>
                    </div>
                    @endif
                    
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex items-baseline mb-2">
                            <span class="text-3xl font-bold text-blue-600">
                                ¥{{ number_format($service->price) }}
                            </span>
                            @if($service->unit)
                            <span class="text-gray-500 ml-2 text-lg">
                                / {{ $service->unit }}
                            </span>
                            @endif
                        </div>
                        
                        @if($service->stock > 0)
                        <p class="text-sm text-gray-600">
                            在庫: {{ $service->stock }}{{ $service->unit ?? '' }}
                        </p>
                        @elseif($service->stock == 0)
                        <p class="text-sm text-red-600 font-semibold">
                            在庫切れ
                        </p>
                        @endif
                        
                        @if($service->minimum && $service->minimum > 1)
                        <p class="text-sm text-gray-600 mt-1">
                            最小注文数: {{ $service->minimum }}{{ $service->unit ?? '' }}
                        </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('services.store') }}">
                        @csrf
                        
                        @if($reservation)
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-blue-800">
                                予約ID: {{ $reservation->id }} に関連付けて注文します
                            </p>
                        </div>
                        @endif
                        
                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                        
                        <!-- サービスオプション -->
                        @if($service->serviceOptions->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                オプション
                            </label>
                            <select name="service_option_id" 
                                    id="service_option_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    onchange="updateTotalPrice()">
                                <option value="">オプションなし</option>
                                @foreach($service->serviceOptions as $option)
                                <option value="{{ $option->id }}" data-price="{{ $option->price }}">
                                    {{ $option->title }} (+¥{{ number_format($option->price) }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <!-- 合計金額表示 -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg" id="total_price_section" style="display: none;">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-700">合計金額</span>
                                <span class="text-2xl font-bold text-blue-600" id="total_price_display">
                                    ¥0
                                </span>
                            </div>
                        </div>
                        
                        <!-- 数量 -->
                        <div class="mb-6">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                数量 <span class="text-red-500">*</span>
                                @if($service->minimum)
                                <span class="text-sm text-gray-500">(最小: {{ $service->minimum }}{{ $service->unit ?? '' }})</span>
                                @endif
                            </label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity"
                                   value="{{ old('quantity', $service->minimum ?? 1) }}"
                                   min="{{ $service->minimum ?? 1 }}"
                                   max="{{ $service->stock > 0 ? $service->stock : 999 }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('quantity') border-red-500 @enderror"
                                   onchange="updateTotalPrice()"
                                   oninput="updateTotalPrice()">
                            @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- エラー表示 -->
                        @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        
                        <!-- ボタン -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('services.index') }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                キャンセル
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-lg font-semibold">
                                カートに追加
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function updateTotalPrice() {
            const basePrice = {{ $service->price }};
            const quantityInput = document.getElementById('quantity');
            const optionSelect = document.getElementById('service_option_id');
            const totalPriceSection = document.getElementById('total_price_section');
            const totalPriceDisplay = document.getElementById('total_price_display');
            
            if (!quantityInput || !totalPriceSection || !totalPriceDisplay) {
                return;
            }
            
            const quantity = parseInt(quantityInput.value) || 0;
            let optionPrice = 0;
            
            if (optionSelect && optionSelect.value) {
                const selectedOption = optionSelect.options[optionSelect.selectedIndex];
                optionPrice = parseInt(selectedOption.getAttribute('data-price')) || 0;
            }
            
            const unitPrice = basePrice + optionPrice;
            const totalPrice = unitPrice * quantity;
            
            if (quantity > 0 && totalPrice > 0) {
                totalPriceSection.style.display = 'block';
                totalPriceDisplay.textContent = '¥' + totalPrice.toLocaleString();
            } else {
                totalPriceSection.style.display = 'none';
            }
        }
        
        // ページ読み込み時に実行
        document.addEventListener('DOMContentLoaded', function() {
            updateTotalPrice();
        });
    </script>
</x-app-layout>

