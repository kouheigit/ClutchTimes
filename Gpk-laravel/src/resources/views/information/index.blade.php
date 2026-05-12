<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            情報
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($information->count() > 0)
                        <div class="space-y-4">
                            @foreach($information as $item)
                            <div class="border rounded-lg p-4 hover:shadow-md transition mb-4">
                                <a href="{{ route('information.show', $item) }}" class="block">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                            <h3 class="text-lg font-semibold mb-2 hover:text-blue-600 transition">{{ $item->title }}</h3>
                                            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($item->body, 150) }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($item->publish_date)->format('Y年m月d日') }}({{ \Carbon\Carbon::parse($item->publish_date)->locale('ja')->isoFormat('ddd') }})
                                            </p>
                                        </div>
                                        <div class="ml-4">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- ページネーション -->
                        <div class="mt-6">
                            {{ $information->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">情報はありません。</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

