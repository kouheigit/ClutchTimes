<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            レポート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 予約レポート -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">予約レポート</h3>
                        <p class="text-gray-600 mb-4">
                            予約の統計情報を確認できます。
                        </p>
                        <a href="{{ route('reports.reservations') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            予約レポートを見る
                        </a>
                    </div>
                </div>

                <!-- 注文レポート -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">注文レポート</h3>
                        <p class="text-gray-600 mb-4">
                            注文の統計情報を確認できます。
                        </p>
                        <a href="{{ route('reports.orders') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            注文レポートを見る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

