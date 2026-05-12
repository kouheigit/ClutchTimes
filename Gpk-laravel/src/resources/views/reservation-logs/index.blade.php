<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約ログ一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- フィルター -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('reservation-logs.index') }}" class="flex gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">予約ID</label>
                            <input type="number" name="reservation_id" value="{{ request('reservation_id') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">アクション</label>
                            <select name="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">すべて</option>
                                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>作成</option>
                                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>更新</option>
                                <option value="cancel" {{ request('action') == 'cancel' ? 'selected' : '' }}>キャンセル</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                フィルター
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($reservation_logs->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    予約ID
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    アクション
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    日時
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    操作
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reservation_logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($log->reservation)
                                        <a href="{{ route('reservation.show', $log->reservation) }}" class="text-blue-600 hover:text-blue-800">
                                            #{{ $log->reservation->id }}
                                        </a>
                                    @else
                                        #{{ $log->reservation_id }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $log->action }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->created_at->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('reservation-logs.show', $log) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        詳細
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4">
                    {{ $reservation_logs->links() }}
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    予約ログが見つかりませんでした。
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

