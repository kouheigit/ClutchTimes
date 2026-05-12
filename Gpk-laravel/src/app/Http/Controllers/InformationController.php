<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InformationController extends Controller
{
    /**
     * 情報一覧表示
     */
    public function index()
    {
        $information = Information::where('status', 1)
            ->where('publish_date', '<=', Carbon::now())
            ->orderBy('publish_date', 'desc')
            ->orderBy('sort', 'asc')
            ->paginate(10);
        
        return view('information.index', compact('information'));
    }
    
    /**
     * 情報詳細表示
     */
    public function show(Information $information)
    {
        // 公開中かつ公開日が過ぎているもののみ表示
        if ($information->status != 1 || $information->publish_date > Carbon::now()) {
            abort(404);
        }
        
        return view('information.show', compact('information'));
    }
}

