<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Veritransログ詳細 #{{ $veritransLog->id }}
            </h2>
            <a href="{{ route('veritrans-logs.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ログID</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $veritransLog->id }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
                            <dd class="mt-1">
                                @if($veritransLog->txn_status == 'capture')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        決済完了
                                    </span>
                                @elseif($veritransLog->txn_status == 'authorize')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        承認済み
                                    </span>
                                @elseif($veritransLog->txn_status == 'cancel')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        キャンセル
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $veritransLog->txn_status }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">金額</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">
                                ¥{{ number_format($veritransLog->amount) }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">日時</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $veritransLog->created_at->format('Y年m月d日 H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

