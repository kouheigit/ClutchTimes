<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">419</h1>
            <h2 class="text-xl font-semibold text-gray-600 mb-4">ページの有効期限が切れました</h2>
            <p class="text-gray-500 mb-8">
                セキュリティのため、このページの有効期限が切れました。<br>
                ページを再読み込みして、もう一度お試しください。
            </p>
            <div class="space-y-3">
                <a href="javascript:location.reload()" 
                   class="block w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition">
                    ページを再読み込み
                </a>
                <a href="{{ route('top') }}" 
                   class="block w-full bg-gray-200 text-gray-700 py-3 px-6 rounded-md hover:bg-gray-300 transition">
                    トップページに戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

