<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            招待作成
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('invitation.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← 招待一覧に戻る
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('invitation.store') }}">
                        @csrf
                        
                        <!-- 予約選択 -->
                        <div class="mb-6">
                            <label for="reservation_id" class="block text-sm font-medium text-gray-700 mb-2">
                                予約を選択 <span class="text-red-500">*</span>
                            </label>
                            <select name="reservation_id" 
                                    id="reservation_id"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('reservation_id') border-red-500 @enderror">
                                <option value="">予約を選択してください</option>
                                @foreach($reservations as $res)
                                <option value="{{ $res->id }}" {{ old('reservation_id', $reservation?->id) == $res->id ? 'selected' : '' }}>
                                    予約ID: {{ $res->id }} - 
                                    {{ \Carbon\Carbon::parse($res->checkin_date)->format('Y/m/d') }} ～
                                    {{ \Carbon\Carbon::parse($res->checkout_date)->format('m/d') }}
                                    @if($res->hotel) - {{ $res->hotel->name }} @endif
                                </option>
                                @endforeach
                            </select>
                            @error('reservation_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- 招待先名前 -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                招待先のお名前 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- 招待先メールアドレス -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                招待先のメールアドレス <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email"
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
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
                            <a href="{{ route('invitation.index') }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                キャンセル
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-lg font-semibold">
                                招待を送信
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

