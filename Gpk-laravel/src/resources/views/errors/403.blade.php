<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">403</h1>
            <h2 class="text-xl font-semibold text-gray-600 mb-4">アクセス権限がありません</h2>
            <p class="text-gray-500 mb-8">
                このページにアクセスする権限がありません。<br>
                必要な権限をお持ちでない可能性があります。
            </p>
            <div class="space-y-3">
                <a href="{{ route('top') }}" 
                   class="block w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition">
                    トップページに戻る
                </a>
                <a href="javascript:history.back()" 
                   class="block w-full bg-gray-200 text-gray-700 py-3 px-6 rounded-md hover:bg-gray-300 transition">
                    前のページに戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

