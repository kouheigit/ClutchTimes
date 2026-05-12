<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ポイント履歴
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('mypage.index') }}" class="text-blue-600 hover:text-blue-800">
                    ← マイページに戻る
                </a>
            </div>

            <!-- ポイント残高 -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">保有ポイント</h3>
                    <p class="text-4xl font-bold">{{ number_format($user_point ?? 0) }} P</p>
                </div>
            </div>

            <!-- ポイント残高詳細 -->
            @if($pointbalance->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">ポイント残高詳細（有効期限別）</h3>
                    
                    <div class="space-y-3">
                        @foreach($pointbalance as $balance)
                        <div class="flex justify-between items-center border-b pb-3">
                            <div>
                                <p class="font-medium">{{ number_format($balance->point) }}P</p>
                                <p class="text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($balance->from)->format('Y年m月d日') }} ～
                                    {{ \Carbon\Carbon::parse($balance->to)->format('Y年m月末日') }}まで有効
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- ポイント履歴 -->
            @if($pointlogs->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">ポイント履歴</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        日時
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        種別
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ポイント
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        理由
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pointlogs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('Y年m月d日 H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($log->type == 1)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                獲得
                                            </span>
                                        @elseif($log->type == 2)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                利用
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                その他
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                        @if($log->type == 1) text-green-600
                                        @elseif($log->type == 2) text-red-600
                                        @else text-gray-600
                                        @endif">
                                        @if($log->type == 1)+@else-@endif{{ number_format($log->point) }}P
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $log->reason ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- ページネーション -->
                    <div class="mt-6">
                        {{ $pointlogs->links() }}
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-gray-600">ポイント履歴がありません</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

