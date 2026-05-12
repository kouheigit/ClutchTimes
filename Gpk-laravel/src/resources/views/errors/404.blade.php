<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">404</h1>
            <h2 class="text-xl font-semibold text-gray-600 mb-4">ページが見つかりません</h2>
            <p class="text-gray-500 mb-8">
                お探しのページは存在しないか、移動または削除された可能性があります。
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

