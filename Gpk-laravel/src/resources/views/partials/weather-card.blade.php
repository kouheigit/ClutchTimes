@props(['weather', 'forecast' => null])

@if($weather)
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-4">今日の天気（軽井沢）</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="flex items-center">
                    @if(isset($weather['weather_icon']))
                    <img src="http://openweathermap.org/img/wn/{{ $weather['weather_icon'] }}@2x.png" alt="天気アイコン" class="w-16 h-16">
                    @endif
                    <div class="ml-4">
                        <p class="text-3xl font-bold">{{ $weather['temp'] }}°C</p>
                        <p class="text-lg">{{ $weather['weather'] }}</p>
                        <p class="text-sm text-gray-600">{{ $weather['description'] }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">最高気温</p>
                    <p class="text-xl font-semibold">{{ $weather['temp_max'] }}°C</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">最低気温</p>
                    <p class="text-xl font-semibold">{{ $weather['temp_min'] }}°C</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">湿度</p>
                    <p class="text-xl font-semibold">{{ $weather['humidity'] }}%</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">風速</p>
                    <p class="text-xl font-semibold">{{ $weather['wind_speed'] }}m/s</p>
                </div>
            </div>
        </div>
        
        @if($forecast && count($forecast) > 0)
        <div class="mt-6 pt-6 border-t">
            <h4 class="text-md font-semibold mb-3">週間天気予報</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($forecast as $day)
                <div class="text-center">
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($day['date'])->format('m/d') }}</p>
                    @if(isset($day['weather_icon']))
                    <img src="http://openweathermap.org/img/wn/{{ $day['weather_icon'] }}@2x.png" alt="天気アイコン" class="w-12 h-12 mx-auto">
                    @endif
                    <p class="text-sm font-semibold">{{ $day['temp_max'] }}° / {{ $day['temp_min'] }}°</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif




















