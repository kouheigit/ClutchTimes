<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            招待管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold">招待一覧</h3>
                <a href="{{ route('invitation.create') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    新しい招待を作成
                </a>
            </div>

            @if($invitations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        招待先
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        予約情報
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ステータス
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        作成日
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        操作
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invitations as $invitation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $invitation->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $invitation->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($invitation->reservation)
                                        <div class="text-sm text-gray-900">
                                            予約ID: {{ $invitation->reservation->id }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($invitation->reservation->checkin_date)->format('Y/m/d') }} ～
                                            {{ \Carbon\Carbon::parse($invitation->reservation->checkout_date)->format('m/d') }}
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($invitation->status == 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            送信済み
                                        </span>
                                        @elseif($invitation->status == 2)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            登録済み
                                        </span>
                                        @if($invitation->user)
                                        <div class="text-xs text-gray-500 mt-1">
                                            ユーザーID: {{ $invitation->user->id }}
                                        </div>
                                        @endif
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            不明
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($invitation->created_at)->format('Y/m/d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($invitation->status == 1)
                                        <form method="POST" action="{{ route('invitation.destroy', $invitation) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('招待を削除しますか？')"
                                                    class="text-red-600 hover:text-red-900">
                                                削除
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- ページネーション -->
                    <div class="mt-6">
                        {{ $invitations->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <p class="text-gray-500 py-8">招待がありません</p>
                    <a href="{{ route('invitation.create') }}" 
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        新しい招待を作成
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

