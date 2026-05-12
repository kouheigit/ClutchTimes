<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            お知らせ詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($news->publish_date)->format('Y年m月d日') }}
                        </p>
                    </div>
                    
                    <h1 class="text-2xl font-bold mb-6">{{ $news->title }}</h1>
                    
                    <div class="prose max-w-none">
                        <p class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $news->body }}</p>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('news.index') }}" class="text-blue-600 hover:text-blue-800">
                            ← お知らせ一覧に戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

