<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\Request;

class ServiceApiController extends Controller
{
    /**
     * サービス一覧取得
     */
    public function index(Request $request)
    {
        $query = Service::with('serviceOptions');
        
        // ステータスフィルター
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // タブフィルター（1: 事前予約、2: 現地注文）
        if ($request->has('tab')) {
            $query->where('tab', $request->tab);
        }
        
        // ホテルIDフィルター
        if ($request->has('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }
        
        // ソート
        $sort = $request->get('sort', 'sort');
        $order = $request->get('order', 'asc');
        $query->orderBy($sort, $order);
        
        $services = $query->paginate($request->get('per_page', 20));
        
        return ServiceResource::collection($services);
    }
    
    /**
     * サービス詳細取得
     */
    public function show($id)
    {
        $service = Service::with('serviceOptions')->findOrFail($id);
        
        return new ServiceResource($service);
    }
}




















