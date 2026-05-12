<?php

namespace App\Http\Controllers;

use App\Models\Freeday;
use App\Services\FreedayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FreedayController extends Controller
{
    private $freeday_service;
    
    public function __construct(FreedayService $freeday_service)
    {
        $this->freeday_service = $freeday_service;
    }
    
    /**
     * FREEDAY一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // FREEDAYS取得
        $freedays = $this->freeday_service->getFreedays($user);
        
        return view('freedays.index', compact('freedays'));
    }
    
    /**
     * FREEDAY詳細表示
     */
    public function show(Freeday $freeday)
    {
        $user = Auth::user();
        
        // 自分のFREEDAYか確認
        if ($freeday->user_id !== $user->id) {
            abort(403, 'このFREEDAYを表示する権限がありません');
        }
        
        return view('freedays.show', compact('freeday'));
    }
}

