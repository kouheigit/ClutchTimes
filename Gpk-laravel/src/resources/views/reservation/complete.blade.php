<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約完了
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        予約が完了しました
                    </h2>
                    
                    @if(session('reservation_id'))
                    <p class="text-lg text-gray-600 mb-2">
                        予約ID: {{ session('reservation_id') }}
                    </p>
                    @endif
                    
                    <p class="text-gray-600 mb-6">
                        予約詳細は予約一覧ページから確認できます。
                    </p>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('reservation.index') }}" 
                           class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            予約一覧へ
                        </a>
                        <a href="{{ route('top') }}" 
                           class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            トップへ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

