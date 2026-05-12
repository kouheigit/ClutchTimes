<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                FREEDAY詳細
            </h2>
            <a href="{{ route('freedays.index') }}" 
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
                            <dt class="text-sm font-medium text-gray-500">日付</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($freeday->date)->format('Y年m月d日') }}
                                ({{ \Carbon\Carbon::parse($freeday->date)->locale('ja')->isoFormat('ddd') }})
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ポイント</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ number_format($freeday->point) }}ポイント
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">作成日時</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $freeday->created_at->format('Y年m月d日 H:i') }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">更新日時</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $freeday->updated_at->format('Y年m月d日 H:i') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

