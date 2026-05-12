<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            通知
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(count($notifications) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($notifications as $notification)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold mb-2">{{ $notification->title ?? '通知' }}</h4>
                                    <p class="text-sm text-gray-600">{{ $notification->body ?? '' }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        {{ $notification->created_at->format('Y年m月d日 H:i') }}
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                            既読にする
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    通知がありません。
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

