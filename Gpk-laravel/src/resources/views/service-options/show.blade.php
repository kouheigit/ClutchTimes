<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                サービスオプション詳細
            </h2>
            <a href="{{ route('service-options.index') }}" 
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
                            <dt class="text-sm font-medium text-gray-500">サービス</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($serviceOption->service)
                                    <a href="{{ route('services.show', $serviceOption->service) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $serviceOption->service->title }}
                                    </a>
                                @else
                                    -
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">タイトル</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $serviceOption->title }}</dd>
                        </div>
                        
                        @if($serviceOption->price)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">価格</dt>
                            <dd class="mt-1 text-sm text-gray-900">¥{{ number_format($serviceOption->price) }}</dd>
                        </div>
                        @endif
                        
                        @if($serviceOption->body)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 mb-2">説明</dt>
                            <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $serviceOption->body }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

