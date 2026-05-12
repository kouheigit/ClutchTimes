<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6">ご招待ありがとうございます</h2>
            
            @if($invitation && $reservation)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold mb-2">予約情報</h3>
                @if($reservation->hotel)
                <p class="text-sm">施設: {{ $reservation->hotel->name }}</p>
                @endif
                <p class="text-sm">
                    チェックイン: {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('Y年m月d日') }}
                </p>
                <p class="text-sm">
                    チェックアウト: {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('Y年m月d日') }}
                </p>
            </div>
            @endif

            <form method="POST" action="{{ route('invitation.register.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $invitation->token }}">
                
                <!-- 名前 -->
                <div class="mb-4">
                    <x-label for="name" :value="__('お名前')" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $invitation->name)" required autofocus />
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- メールアドレス -->
                <div class="mb-4">
                    <x-label for="email" :value="__('メールアドレス')" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $invitation->email)" required />
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- パスワード -->
                <div class="mb-4">
                    <x-label for="password" :value="__('パスワード')" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- パスワード確認 -->
                <div class="mb-4">
                    <x-label for="password_confirmation" :value="__('パスワード確認')" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                </div>
                
                <!-- 姓 -->
                <div class="mb-4">
                    <x-label for="last_name" :value="__('姓')" />
                    <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                    @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 名 -->
                <div class="mb-4">
                    <x-label for="first_name" :value="__('名')" />
                    <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required />
                    @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- 電話番号 -->
                <div class="mb-4">
                    <x-label for="tel" :value="__('電話番号')" />
                    <x-input id="tel" class="block mt-1 w-full" type="text" name="tel" :value="old('tel')" required />
                    @error('tel')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- エラー表示 -->
                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="flex items-center justify-end mt-6">
                    <x-button class="ml-4">
                        {{ __('登録する') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

