<?php
// app/Services/TrafficService.php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TrafficService
{
    private $api_key;
    private $base_url = 'https://maps.googleapis.com/maps/api/directions/json';
    
    public function __construct()
    {
        $this->api_key = env('GOOGLE_MAPS_API_KEY', 'AIzaSyDLOarhzAaiOZ27LhuWihrS1b2WOgJTxBY');
    }
    
    /**
     * 交通・渋滞情報を取得（キャッシュ10分）
     */
    public function getTrafficInfo(
        $departure = '軽井沢駅',
        $destination = '三ツ谷地区世代間交流センター、長野県北佐久郡御代田町馬瀬口2039-2'
    ) {
        $cache_key = 'traffic_' . md5($departure . $destination);
        
        return Cache::remember($cache_key, 600, function () use ($departure, $destination) {
            try {
                $client = new Client();
                
                $response = $client->get($this->base_url, [
                    'query' => [
                        'origin' => $departure,
                        'destination' => $destination,
                        'departure_time' => 'now',  // リアルタイム
                        'mode' => 'driving',
                        'language' => 'ja',
                        'alternatives' => 'true',
                        'key' => $this->api_key,
                    ]
                ]);
                
                $data = json_decode($response->getBody(), true);
                
                if ($data['status'] !== 'OK' || empty($data['routes'])) {
                    return null;
                }
                
                $route = $data['routes'][0];
                $leg = $route['legs'][0];
                
                return [
                    'distance' => $leg['distance']['text'],
                    'duration' => $leg['duration']['text'],
                    'duration_in_traffic' => $leg['duration_in_traffic']['text'] ?? $leg['duration']['text'],
                    'traffic_status' => $this->determineTraffic($route),
                    'start_address' => $leg['start_address'],
                    'end_address' => $leg['end_address'],
                    'route_name' => $route['summary'],
                ];
            } catch (\Exception $e) {
                Log::error('Traffic API Error: ' . $e->getMessage());
                return null;
            }
        });
    }
    
    /**
     * 渋滞判定
     */
    private function determineTraffic($route)
    {
        foreach ($route['legs'] as $leg) {
            foreach ($leg['steps'] as $step) {
                if (isset($step['traffic_speed_entry'])) {
                    $speed = $step['traffic_speed_entry'][0]['speed'];
                    
                    if ($speed < 20) {
                        return ['status' => 'heavy', 'text' => '渋滞あり', 'color' => 'red'];
                    } elseif ($speed < 40) {
                        return ['status' => 'moderate', 'text' => 'やや混雑', 'color' => 'orange'];
                    }
                }
            }
        }
        
        return ['status' => 'clear', 'text' => '渋滞なし', 'color' => 'green'];
    }
}

