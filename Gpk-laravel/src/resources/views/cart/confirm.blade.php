<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            注文確認
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('cart.store', $cart) }}">
                @csrf
                
                <!-- 注文内容 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">注文内容</h3>
                        
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
                                            <p>数量: {{ $detail->quantity }}</p>
                                            <p>単価: ¥{{ number_format($detail->price) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold">¥{{ number_format($detail->total_price) }}</p>
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">予約に関連付ける（任意）</h3>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="link_reservation" 
                                   id="link_reservation"
                                   value="1"
                                   onchange="toggleReservationSelect()"
                                   class="mr-2">
                            <label for="link_reservation" class="text-sm">
                                予約ID {{ $last_reservation->id }} に関連付ける
                                ({{ \Carbon\Carbon::parse($last_reservation->checkin_date)->format('Y年m月d日') }} ～ 
                                {{ \Carbon\Carbon::parse($last_reservation->checkout_date)->format('m月d日') }})
                            </label>
                        </div>
                        <input type="hidden" name="reservation_id" id="reservation_id" value="{{ $last_reservation->id ?? '' }}">
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
                    <a href="{{ route('cart.index') }}" 
                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        ← カートに戻る
                    </a>
                    <button type="submit" 
                            onclick="return confirm('注文を確定しますか？')"
                            class="px-8 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 text-lg font-semibold">
                        注文を確定する
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCreditCardForm() {
            const paymentCredit = document.getElementById('payment_credit');
            const creditCardForm = document.getElementById('credit_card_form');
            
            if (paymentCredit && creditCardForm) {
                if (paymentCredit.checked) {
                    creditCardForm.style.display = 'block';
                } else {
                    creditCardForm.style.display = 'none';
                }
            }
        }
        
        function toggleReservationSelect() {
            const checkbox = document.getElementById('link_reservation');
            const reservationId = document.getElementById('reservation_id');
            
            if (checkbox && reservationId) {
                if (checkbox.checked) {
                    reservationId.value = {{ $last_reservation->id ?? 'null' }};
                } else {
                    reservationId.value = '';
                }
            }
        }
        
        // フォーム送信時の処理
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const checkbox = document.getElementById('link_reservation');
            const reservationId = document.getElementById('reservation_id');
            
            if (form && checkbox && reservationId) {
                form.addEventListener('submit', function(e) {
                    // チェックボックスの状態に応じてreservation_idを設定
                    if (!checkbox.checked) {
                        reservationId.value = '';
                    }
                });
            }
            
            // 初期表示時にクレジットカードフォームの表示状態を設定
            toggleCreditCardForm();
        });
    </script>
</x-app-layout>

