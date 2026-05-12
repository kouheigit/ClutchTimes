<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PointBalanceResource;
use App\Http\Resources\PointLogResource;
use App\Models\UserPoint;
use App\Models\UserPointLog;
use App\Services\PointService;
use Illuminate\Http\Request;

class PointApiController extends Controller
{
    private $point_service;
    
    public function __construct(PointService $point_service)
    {
        $this->point_service = $point_service;
    }
    
    /**
     * ポイント残高取得
     */
    public function balance(Request $request)
    {
        $user = $request->user();
        
        // ポイント残高取得
        $user_point = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント残高詳細（有効期限別）
        $pointbalance = UserPoint::where('user_id', $user->id)
            ->where('to', '>=', now()->format('Y-m-d'))
            ->where('point', '>', 0)
            ->orderBy('to', 'asc')
            ->get();
        
        return response()->json([
            'total_points' => $user_point,
            'balance_by_expiry' => PointBalanceResource::collection($pointbalance)
        ]);
    }
    
    /**
     * ポイント履歴取得
     */
    public function history(Request $request)
    {
        $user = $request->user();
        
        $query = UserPointLog::where('user_id', $user->id);
        
        // タイプフィルター
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        // 日付範囲フィルター
        if ($request->has('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        
        if ($request->has('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }
        
        $pointlogs = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));
        
        return PointLogResource::collection($pointlogs)->additional([
            'pagination' => [
                'current_page' => $pointlogs->currentPage(),
                'last_page' => $pointlogs->lastPage(),
                'per_page' => $pointlogs->perPage(),
                'total' => $pointlogs->total(),
            ]
        ]);
    }
}


