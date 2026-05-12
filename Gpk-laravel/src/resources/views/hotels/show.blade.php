<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $hotel->name }}
            </h2>
            <a href="{{ route('hotels.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">ホテル情報</h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($hotel->address)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">住所</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hotel->address }}</dd>
                        </div>
                        @endif
                        
                        @if($hotel->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">電話番号</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hotel->phone }}</dd>
                        </div>
                        @endif
                        
                        @if($hotel->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">メールアドレス</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $hotel->email }}</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ステータス</dt>
                            <dd class="mt-1">
                                @if($hotel->status == 1)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        公開中
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        非公開
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                    
                    @if($hotel->description)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500 mb-2">説明</dt>
                        <dd class="text-sm text-gray-900 whitespace-pre-line">{{ $hotel->description }}</dd>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($services->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">提供サービス</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($services as $service)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <h4 class="font-semibold mb-2">
                                <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $service->title }}
                                </a>
                            </h4>
                            
                            @if($service->price)
                            <p class="text-sm text-gray-600 mb-2">
                                価格: ¥{{ number_format($service->price) }}
                            </p>
                            @endif
                            
                            @if($service->body)
                            <p class="text-sm text-gray-700 mb-2 line-clamp-2">
                                {{ Str::limit($service->body, 80) }}
                            </p>
                            @endif
                            
                            <a href="{{ route('services.show', $service) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                詳細を見る →
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            @if($calendars->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">カレンダー</h3>
                    
                    <div class="space-y-2">
                        @foreach($calendars->take(10) as $calendar)
                        <div class="border rounded-lg p-3 hover:bg-gray-50">
                            <p class="text-sm">
                                {{ \Carbon\Carbon::parse($calendar->start_date)->format('Y年m月d日') }}
                                ～
                                {{ \Carbon\Carbon::parse($calendar->end_date)->format('m月d日') }}
                            </p>
                        </div>
                        @endforeach
                        
                        @if($calendars->count() > 10)
                        <p class="text-sm text-gray-500 text-center mt-4">
                            他 {{ $calendars->count() - 10 }}件のカレンダーがあります
                        </p>
                        @endif
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('calendar.index') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            カレンダー一覧を見る
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

