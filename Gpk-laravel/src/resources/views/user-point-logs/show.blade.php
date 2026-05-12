<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ポイントログ詳細
            </h2>
            <a href="{{ route('user-point-logs.index') }}" 
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
                            <dt class="text-sm font-medium text-gray-500">タイプ</dt>
                            <dd class="mt-1">
                                @if($userPointLog->type == 1)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        付与
                                    </span>
                                @elseif($userPointLog->type == 2)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        利用
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        その他
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ポイント</dt>
                            <dd class="mt-1 text-lg font-semibold {{ $userPointLog->type == 1 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $userPointLog->type == 1 ? '+' : '-' }}{{ number_format($userPointLog->point) }}ポイント
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">説明</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $userPointLog->note ?? '-' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">日時</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $userPointLog->created_at->format('Y年m月d日 H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

