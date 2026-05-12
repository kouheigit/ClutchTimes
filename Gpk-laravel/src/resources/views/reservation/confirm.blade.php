<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約確認
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('reservation.store') }}">
                @csrf
                
                <!-- 予約情報 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">予約情報</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">チェックイン</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($reservation_data['checkin_date'])->format('Y年m月d日') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">チェックアウト</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($reservation_data['checkout_date'])->format('Y年m月d日') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">宿泊日数</span>
                                <span class="font-medium">{{ $reservation_data['days'] }}泊</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">宿泊人数</span>
                                <span class="font-medium">
                                    大人{{ $reservation_data['adult'] }}名
                                    @if($reservation_data['child'] > 0) / 子供{{ $reservation_data['child'] }}名 @endif
                                    @if($reservation_data['dog'] > 0) / 犬{{ $reservation_data['dog'] }}頭 @endif
                                </span>
                            </div>
                            @if($reservation_data['note'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">備考</span>
                                <span class="font-medium">{{ $reservation_data['note'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- サービス注文 -->
                @if($tmp_orders->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">サービス注文</h3>
                        
                        <div class="space-y-4">
                            @foreach($tmp_orders as $tmp_order)
                            <div class="flex justify-between items-center border-b pb-4">
                                <div>
                                    <p class="font-medium">{{ $tmp_order->service->title ?? 'サービス' }}</p>
                                    @if($tmp_order->serviceOption)
                                    <p class="text-sm text-gray-600">{{ $tmp_order->serviceOption->title }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">
                                        数量: {{ $tmp_order->quantity }} × ¥{{ number_format($tmp_order->price) }}
                                    </p>
                                </div>
                                <p class="text-lg font-semibold">¥{{ number_format($tmp_order->total_price) }}</p>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 pt-6 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-semibold">サービス合計</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    ¥{{ number_format($service_total) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 決済方法 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">決済方法</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" name="payment" value="0" id="payment_cash" 
                                       {{ old('payment', 0) == 0 ? 'checked' : '' }} 
                                       class="mr-2" onchange="toggleCreditCardForm()">
                                <label for="payment_cash" class="text-lg">現地払い</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" name="payment" value="1" id="payment_credit" 
                                       {{ old('payment') == 1 ? 'checked' : '' }} 
                                       class="mr-2" onchange="toggleCreditCardForm()">
                                <label for="payment_credit" class="text-lg">クレジットカード</label>
                            </div>
                        </div>
                        
                        <!-- クレジットカード情報フォーム -->
                        <div id="credit_card_form" style="display: {{ old('payment') == 1 ? 'block' : 'none' }};" class="mt-6 pt-6 border-t">
                            <h4 class="text-md font-semibold mb-4">クレジットカード情報</h4>
                            
                            @if($errors->has('card_error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ $errors->first('card_error') }}
                            </div>
                            @endif
                            
                            <div class="space-y-4">
                                <!-- カード番号 -->
                                <div>
                                    <label for="card_number" class="block text-sm font-medium text-gray-700 mb-1">
                                        カード番号 <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="card_number" 
                                           id="card_number"
                                           value="{{ old('card_number') }}"
                                           placeholder="1234 5678 9012 3456"
                                           maxlength="19"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('card_number') border-red-500 @enderror">
                                    @error('card_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- 有効期限 -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="card_expire" class="block text-sm font-medium text-gray-700 mb-1">
                                            有効期限 <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="card_expire" 
                                               id="card_expire"
                                               value="{{ old('card_expire') }}"
                                               placeholder="MM/YY"
                                               maxlength="5"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('card_expire') border-red-500 @enderror">
                                        @error('card_expire')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- セキュリティコード -->
                                    <div>
                                        <label for="security_code" class="block text-sm font-medium text-gray-700 mb-1">
                                            CVV <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="security_code" 
                                               id="security_code"
                                               value="{{ old('security_code') }}"
                                               placeholder="123"
                                               maxlength="4"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('security_code') border-red-500 @enderror">
                                        @error('security_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- トークン（将来のトークン決済用） -->
                                <input type="hidden" name="token" id="token" value="{{ old('token') }}">
                                
                                <p class="text-xs text-gray-500 mt-2">
                                    ※ カード情報は暗号化されて安全に送信されます
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ポイント利用 -->
                @if($available_points > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">ポイント利用</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-2">
                                    利用可能ポイント: <span class="font-semibold text-blue-600">{{ number_format($available_points) }}P</span>
                                </p>
                                <p class="text-sm text-gray-600 mb-4">
                                    最大利用可能: <span class="font-semibold">{{ number_format($max_point_use) }}P</span> (合計金額まで)
                                </p>
                            </div>
                            
                            <div>
                                <label for="use_point" class="block text-sm font-medium text-gray-700 mb-2">
                                    利用ポイント数
                                </label>
                                <input type="number" 
                                       name="use_point" 
                                       id="use_point"
                                       value="{{ old('use_point', 0) }}"
                                       min="0"
                                       max="{{ $max_point_use }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('use_point') border-red-500 @enderror"
                                       onchange="updateTotalPrice()">
                                <p class="mt-1 text-xs text-gray-500">
                                    0P ～ {{ number_format($max_point_use) }}P の範囲で入力してください
                                </p>
                                @error('use_point')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 合計金額 -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-semibold">サービス合計</span>
                            <span class="text-2xl font-bold text-gray-700">
                                ¥{{ number_format($service_total) }}
                            </span>
                        </div>
                        @if($available_points > 0)
                        <div class="flex justify-between items-center" id="point_discount_row" style="display: none;">
                            <span class="text-lg text-gray-600">ポイント割引</span>
                            <span class="text-xl font-semibold text-red-600" id="point_discount">
                                -¥0
                            </span>
                        </div>
                        @endif
                        <div class="pt-2 border-t border-blue-300">
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-semibold">合計金額</span>
                                <span class="text-4xl font-bold text-blue-600" id="total_price_display">
                            ¥{{ number_format($total_price) }}
                        </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ボタン -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('reservation.cart') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        ← カートに戻る
                    </a>
                    <button type="submit" 
                            onclick="return confirm('予約を確定しますか？')"
                            class="px-8 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 text-lg font-semibold">
                        予約を確定する
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function toggleCreditCardForm() {
            const creditCardRadio = document.getElementById('payment_credit');
            const creditCardForm = document.getElementById('credit_card_form');
            
            if (creditCardRadio.checked) {
                creditCardForm.style.display = 'block';
                // 必須項目を設定
                document.getElementById('card_number').required = true;
                document.getElementById('card_expire').required = true;
                document.getElementById('security_code').required = true;
            } else {
                creditCardForm.style.display = 'none';
                // 必須項目を解除
                document.getElementById('card_number').required = false;
                document.getElementById('card_expire').required = false;
                document.getElementById('security_code').required = false;
            }
        }
        
        // カード番号のフォーマット（自動的にスペースを挿入）
        document.addEventListener('DOMContentLoaded', function() {
            const cardNumberInput = document.getElementById('card_number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s/g, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                    e.target.value = formattedValue;
                });
            }
            
            // 有効期限のフォーマット（MM/YY）
            const cardExpireInput = document.getElementById('card_expire');
            if (cardExpireInput) {
                cardExpireInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }
            
            // セキュリティコードは数字のみ
            const securityCodeInput = document.getElementById('security_code');
            if (securityCodeInput) {
                securityCodeInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }
            
            // 初期表示時の状態を設定
            toggleCreditCardForm();
            
            // フォーム送信前にカード番号からスペースを削除
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const cardNumberInput = document.getElementById('card_number');
                    if (cardNumberInput && cardNumberInput.value) {
                        // スペースを削除してから送信
                        cardNumberInput.value = cardNumberInput.value.replace(/\s/g, '');
                    }
                });
            }
        });
        
        // ポイント利用時の合計金額再計算
        function updateTotalPrice() {
            const usePointInput = document.getElementById('use_point');
            const pointDiscountRow = document.getElementById('point_discount_row');
            const pointDiscount = document.getElementById('point_discount');
            const totalPriceDisplay = document.getElementById('total_price_display');
            
            if (!usePointInput || !totalPriceDisplay) {
                return;
            }
            
            const serviceTotal = {{ $service_total }};
            const maxPointUse = {{ $max_point_use ?? 0 }};
            let usePoint = parseInt(usePointInput.value) || 0;
            
            // 最大値チェック
            if (usePoint > maxPointUse) {
                usePoint = maxPointUse;
                usePointInput.value = usePoint;
            }
            
            // 最小値チェック
            if (usePoint < 0) {
                usePoint = 0;
                usePointInput.value = 0;
            }
            
            // ポイント割引額
            const pointDiscountAmount = usePoint;
            
            // 合計金額計算
            const totalPrice = serviceTotal - pointDiscountAmount;
            
            // 表示更新
            if (pointDiscountRow && pointDiscount) {
                if (usePoint > 0) {
                    pointDiscountRow.style.display = 'flex';
                    pointDiscount.textContent = '-¥' + pointDiscountAmount.toLocaleString();
                } else {
                    pointDiscountRow.style.display = 'none';
                }
            }
            
            totalPriceDisplay.textContent = '¥' + totalPrice.toLocaleString();
        }
        
        // ページ読み込み時に実行
        document.addEventListener('DOMContentLoaded', function() {
            const usePointInput = document.getElementById('use_point');
            if (usePointInput) {
                usePointInput.addEventListener('input', updateTotalPrice);
                updateTotalPrice(); // 初期表示時にも実行
            }
        });
    </script>
</x-app-layout>

