<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            設定
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- プロフィール設定 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">プロフィール設定</h3>
                        <p class="text-gray-600 mb-4">
                            名前やメールアドレスなどの基本情報を変更できます。
                        </p>
                        <a href="{{ route('settings.profile') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            プロフィールを編集
                        </a>
                    </div>
                </div>

                <!-- パスワード設定 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">パスワード設定</h3>
                        <p class="text-gray-600 mb-4">
                            パスワードを変更できます。
                        </p>
                        <a href="{{ route('settings.password') }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            パスワードを変更
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

