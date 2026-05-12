<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserPointController extends Controller
{
    private $point_service;
    
    public function __construct(PointService $point_service)
    {
        $this->point_service = $point_service;
    }
    
    /**
     * ユーザーポイント一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // ポイント残高取得
        $user_point = $this->point_service->getAvailablePoints($user->id);
        
        // ポイント残高詳細（有効期限別）
        $user_points = UserPoint::where('user_id', $user->id)
            ->where('to', '>=', Carbon::now()->format('Y-m-d'))
            ->where('point', '>', 0)
            ->orderBy('to', 'asc')
            ->get();
        
        return view('user-points.index', compact('user_point', 'user_points'));
    }
    
    /**
     * ユーザーポイント詳細表示
     */
    public function show(UserPoint $userPoint)
    {
        $user = Auth::user();
        
        // 自分のポイントか確認
        if ($userPoint->user_id !== $user->id) {
            abort(403, 'このポイントを表示する権限がありません');
        }
        
        return view('user-points.show', compact('userPoint'));
    }
}

