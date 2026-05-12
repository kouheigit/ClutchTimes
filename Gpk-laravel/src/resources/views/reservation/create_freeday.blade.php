<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            フリーデイ予約作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- フリーデイ情報 -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-2">FREEDAY情報</h3>
                        <p class="text-lg">
                            利用可能: <span class="text-green-600 font-bold">{{ $freeday->freedays }}泊</span>
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            有効期限: {{ \Carbon\Carbon::parse($freeday->end_date)->format('Y年m月末日') }}まで
                        </p>
                    </div>

                    <form method="POST" action="{{ route('reservation.service') }}">
                        @csrf
                        
                        <input type="hidden" name="freeday_id" value="{{ $freeday->id }}">
                        
                        <!-- 日程選択 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">予約日程</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        チェックイン <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="checkin_date" 
                                           value="{{ old('checkin_date', request('d', now()->format('Y-m-d'))) }}"
                                           min="{{ \Carbon\Carbon::parse($freeday->start_date)->firstOfMonth()->subMonths(18)->format('Y-m-d') }}"
                                           max="{{ $freeday->end_date->format('Y-m-d') }}"
                                           required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        宿泊日数 <span class="text-red-500">*</span>
                                    </label>
                                    <select name="days" id="days" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 1; $i <= min($freeday->freedays, 30); $i++)
                                            <option value="{{ $i }}" {{ old('days', 1) == $i ? 'selected' : '' }}>{{ $i }}泊</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-600" id="checkout_date_display">
                                    チェックアウト: <span id="checkout_date_value"></span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- 人数入力 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">宿泊人数</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        大人 <span class="text-red-500">*</span>
                                    </label>
                                    <select name="adult" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('adult', 2) == $i ? 'selected' : '' }}>{{ $i }}名</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        子供
                                    </label>
                                    <select name="child" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 0; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ old('child', 0) == $i ? 'selected' : '' }}>{{ $i }}名</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        犬
                                    </label>
                                    <select name="dog" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @for($i = 0; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ old('dog', 0) == $i ? 'selected' : '' }}>{{ $i }}頭</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 備考 -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                備考
                            </label>
                            <textarea name="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('note') }}</textarea>
                        </div>
                        
                        <!-- ボタン -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('reservation.index') }}" 
                               class="mr-4 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                キャンセル
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                次へ（サービス選択）
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // チェックアウト日の自動計算
        document.addEventListener('DOMContentLoaded', function() {
            const checkinInput = document.querySelector('input[name="checkin_date"]');
            const daysSelect = document.getElementById('days');
            const checkoutDisplay = document.getElementById('checkout_date_value');
            
            function updateCheckoutDate() {
                if (checkinInput.value && daysSelect.value) {
                    const checkinDate = new Date(checkinInput.value);
                    const days = parseInt(daysSelect.value);
                    const checkoutDate = new Date(checkinDate);
                    checkoutDate.setDate(checkoutDate.getDate() + days);
                    
                    checkoutDisplay.textContent = checkoutDate.toLocaleDateString('ja-JP', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                }
            }
            
            checkinInput.addEventListener('change', updateCheckoutDate);
            daysSelect.addEventListener('change', updateCheckoutDate);
            updateCheckoutDate();
        });
    </script>
</x-app-layout>

