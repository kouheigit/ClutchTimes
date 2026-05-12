<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約作成
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
                    <form method="POST" action="{{ route('reservation.service') }}">
                        @csrf
                        
                        <input type="hidden" name="calendar_id" value="{{ $calendar->id }}">
                        
                        <!-- 日程表示 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-2">予約日程</h3>
                            <div class="bg-blue-50 p-4 rounded">
                                @if($calendar->hotel)
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">ホテル:</span> {{ $calendar->hotel->name }}
                                </p>
                                @endif
                                <p class="text-lg">
                                    <span class="font-medium">チェックイン:</span>
                                    {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日') }}
                                </p>
                                <p class="text-lg mt-1">
                                    <span class="font-medium">チェックアウト:</span>
                                    {{ \Carbon\Carbon::parse($calendar->end_date)->format('Y年m月d日') }}
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    宿泊日数: {{ \Carbon\Carbon::parse($calendar->start_date)->diffInDays($calendar->end_date) }}泊
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
</x-app-layout>

