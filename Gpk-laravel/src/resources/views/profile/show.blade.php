<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                プロフィール
            </h2>
            <a href="{{ route('profile.edit') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                編集
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">名前</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">メールアドレス</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>
                        
                        @if($user->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">電話番号</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->phone }}</dd>
                        </div>
                        @endif
                        
                        @if($user->address)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">住所</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->address }}</dd>
                        </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">登録日</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $user->created_at->format('Y年m月d日') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

