<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                メールテンプレート詳細
            </h2>
            <a href="{{ route('mail-templates.index') }}" 
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
                            <dd class="mt-1 text-sm text-gray-900">{{ $mailTemplate->type }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">タイトル</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mailTemplate->title }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
                            <dd class="mt-1">
                                @if($mailTemplate->status == 1)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        有効
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        無効
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        @if($mailTemplate->subject)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">件名</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mailTemplate->subject }}</dd>
                        </div>
                        @endif
                        
                        @if($mailTemplate->body)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">本文</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $mailTemplate->body }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

