<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カレンダー
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            <!-- 月選択 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('calendar.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            ← {{ $prevMonth->format('Y年m月') }}
                        </a>
                        <h3 class="text-2xl font-semibold">
                            {{ $date->format('Y年m月') }}
                        </h3>
                        <a href="{{ route('calendar.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            {{ $nextMonth->format('Y年m月') }} →
                        </a>
                    </div>
                </div>
            </div>

            <!-- カレンダー表示 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">日</th>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">月</th>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">火</th>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">水</th>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">木</th>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">金</th>
                                <th class="border border-gray-300 p-2 bg-gray-100 text-center">土</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $firstDay = $date->copy()->startOfMonth();
                                $lastDay = $date->copy()->endOfMonth();
                                $startDate = $firstDay->copy()->startOfWeek();
                                $endDate = $lastDay->copy()->endOfWeek();
                                $currentDate = $startDate->copy();
                            @endphp
                            @while($currentDate <= $endDate)
                                <tr>
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $isCurrentMonth = $currentDate->month == $date->month;
                                            $isToday = $currentDate->isToday();
                                            $isHoliday = in_array($currentDate->format('Y-m-d'), $holidays);
                                            $isWeekend = $currentDate->isWeekend();
                                            
                                            // その日のカレンダー情報
                                            $dayCalendar = $calendars->first(function($cal) use ($currentDate) {
                                                return $currentDate->between($cal->start_date, $cal->end_date);
                                            });
                                            
                                            // その日の予約
                                            $dayReservations = $reservations->filter(function($res) use ($currentDate) {
                                                return $currentDate->between($res->checkin_date, $res->checkout_date);
                                            });
                                        @endphp
                                        <td class="border border-gray-300 p-2 align-top h-24 {{ !$isCurrentMonth ? 'bg-gray-50 text-gray-400' : '' }} {{ $isToday ? 'bg-blue-50' : '' }}">
                                            <div class="flex flex-col h-full">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-sm font-medium {{ $isToday ? 'text-blue-600 font-bold' : '' }} {{ $isHoliday || $isWeekend ? 'text-red-600' : '' }}">
                                                        {{ $currentDate->day }}
                                                    </span>
                                                    @if($isHoliday)
                                                    <span class="text-xs text-red-600">祝</span>
                                                    @endif
                                                </div>
                                                <div class="flex-1 text-xs space-y-1">
                                                    @if($dayCalendar)
                                                    <div class="bg-blue-100 text-blue-800 px-1 rounded text-xs">
                                                        @if($dayCalendar->status == 1)
                                                            予約可能
                                                        @elseif($dayCalendar->status == 2)
                                                            予約中
                                                        @elseif($dayCalendar->status == 3)
                                                            予約確定
                                                        @endif
                                                    </div>
                                                    @endif
                                                    @if($dayReservations->count() > 0)
                                                        @foreach($dayReservations->take(2) as $reservation)
                                                        <div class="bg-green-100 text-green-800 px-1 rounded text-xs truncate">
                                                            予約あり
                                                        </div>
                                                        @endforeach
                                                        @if($dayReservations->count() > 2)
                                                        <div class="text-xs text-gray-500">
                                                            +{{ $dayReservations->count() - 2 }}
                                                        </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                @if($isCurrentMonth)
                                                <a href="{{ route('calendar.detail', ['year' => $currentDate->year, 'month' => $currentDate->month, 'day' => $currentDate->day]) }}" 
                                                   class="text-xs text-blue-600 hover:text-blue-800 mt-auto">
                                                    詳細
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                        @php
                                            $currentDate->addDay();
                                        @endphp
                                    @endfor
                                </tr>
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 凡例 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">凡例</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-100 border border-blue-300"></div>
                            <span class="text-sm">予約可能</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-yellow-100 border border-yellow-300"></div>
                            <span class="text-sm">予約中</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-100 border border-green-300"></div>
                            <span class="text-sm">予約確定</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-100 border border-red-300"></div>
                            <span class="text-sm">休日</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-50 border border-blue-200"></div>
                            <span class="text-sm">今日</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

