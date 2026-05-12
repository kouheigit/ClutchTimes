<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">500</h1>
            <h2 class="text-xl font-semibold text-gray-600 mb-4">サーバーエラーが発生しました</h2>
            <p class="text-gray-500 mb-8">
                申し訳ございません。サーバーでエラーが発生しました。<br>
                しばらく時間をおいてから再度お試しください。
            </p>
            <div class="space-y-3">
                <a href="{{ route('top') }}" 
                   class="block w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition">
                    トップページに戻る
                </a>
                <a href="javascript:location.reload()" 
                   class="block w-full bg-gray-200 text-gray-700 py-3 px-6 rounded-md hover:bg-gray-300 transition">
                    ページを再読み込み
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

