<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            登録完了
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold mb-4">会員登録が完了しました</h2>
                    <p class="text-gray-600 mb-8">
                        空ノ庭の会員登録が完了しました。<br>
                        予約情報はマイページからご確認いただけます。
                    </p>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('mypage.index') }}" 
                           class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            マイページへ
                        </a>
                        <a href="{{ route('top') }}" 
                           class="px-6 py-3 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            トップページへ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

