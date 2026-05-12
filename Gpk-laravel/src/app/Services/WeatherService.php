<?php
// app/Services/WeatherService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    private $api_key;
    private $base_url = 'https://api.openweathermap.org/data/2.5';
    
    public function __construct()
    {
        $this->api_key = env('OPENWEATHER_API_KEY', '0176e5633457466d0d5f491966c41fb8');
    }
    
    /**
     * 今日の天気を取得（キャッシュ30分）
     */
    public function getTodayWeather($city = 'Karuizawa,jp')
    {
        return Cache::remember('weather_today_' . $city, 1800, function () use ($city) {
            try {
                $client = new Client();
                $url = "{$this->base_url}/weather?q={$city}&appid={$this->api_key}&lang=ja";
                
                $response = $client->request('GET', $url);
                $data = json_decode($response->getBody(), true);
                
                return [
                    'temp' => round($data['main']['temp'] - 273.15), // ケルビン→摂氏
                    'temp_max' => round($data['main']['temp_max'] - 273.15),
                    'temp_min' => round($data['main']['temp_min'] - 273.15),
                    'weather' => $this->translateWeather($data['weather'][0]['main']),
                    'weather_icon' => $data['weather'][0]['icon'],
                    'description' => $data['weather'][0]['description'],
                    'humidity' => $data['main']['humidity'],
                    'pressure' => $data['main']['pressure'],
                    'wind_speed' => $data['wind']['speed'],
                ];
            } catch (\Exception $e) {
                Log::error('Weather API Error: ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * 5日間予報を取得
     */
    public function getForecast($city = 'Karuizawa,jp', $days = 5)
    {
        return Cache::remember('weather_forecast_' . $city, 1800, function () use ($city, $days) {
            try {
                $client = new Client();
                $url = "{$this->base_url}/forecast?q={$city}&appid={$this->api_key}&lang=ja";
                
                $response = $client->request('GET', $url);
                $data = json_decode($response->getBody(), true);
                
                $forecast = [];
                $previous_date = null;
                
                // 3時間ごとのデータから1日1回（正午）を抽出
                foreach ($data['list'] as $item) {
                    $date = date('Y-m-d', strtotime($item['dt_txt']));
                    $hour = date('H', strtotime($item['dt_txt']));
                    
                    // 1日1回、正午のデータのみ
                    if ($date != $previous_date && $hour == '12') {
                        $forecast[] = [
                            'date' => $date,
                            'date_formatted' => date('m/d (D)', strtotime($item['dt_txt'])),
                            'temp' => round($item['main']['temp'] - 273.15),
                            'temp_max' => round($item['main']['temp_max'] - 273.15),
                            'temp_min' => round($item['main']['temp_min'] - 273.15),
                            'weather' => $this->translateWeather($item['weather'][0]['main']),
                            'weather_icon' => $item['weather'][0]['icon'],
                            'humidity' => $item['main']['humidity'],
                            'pop' => round($item['pop'] * 100), // 降水確率
                        ];
                        
                        $previous_date = $date;
                        
                        if (count($forecast) >= $days) {
                            break;
                        }
                    }
                }
                
                return $forecast;
            } catch (\Exception $e) {
                Log::error('Forecast API Error: ' . $e->getMessage());
                return [];
            }
        });
    }
    
    /**
     * 天気を日本語に変換
     */
    private function translateWeather($weather)
    {
        $translations = [
            'Clear' => '晴れ',
            'Clouds' => 'くもり',
            'Rain' => '雨',
            'Drizzle' => '小雨',
            'Thunderstorm' => '雷雨',
            'Snow' => '雪',
            'Mist' => '霧',
            'Fog' => '霧',
            'Haze' => '霞',
        ];
        
        return $translations[$weather] ?? $weather;
    }
}

