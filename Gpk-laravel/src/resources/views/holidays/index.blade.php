<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            休日一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- フィルター -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('holidays.index') }}" class="flex gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">年</label>
                            <select name="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @for($y = now()->year - 2; $y <= now()->year + 2; $y++)
                                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                        {{ $y }}年
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">月</label>
                            <select name="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">すべて</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                        {{ $m }}月
                                    </option>
                                @endfor
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

            @if($holidays->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    日付
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    名称
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    操作
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($holidays as $holiday)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($holiday->date)->format('Y年m月d日') }}
                                    ({{ \Carbon\Carbon::parse($holiday->date)->locale('ja')->isoFormat('ddd') }})
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $holiday->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('holidays.show', $holiday) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        詳細
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-500">
                    休日が見つかりませんでした。
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

