<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InformationResource;
use App\Models\Information;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InformationApiController extends Controller
{
    /**
     * 情報一覧取得（公開API）
     */
    public function index(Request $request)
    {
        $query = Information::where('status', 1)
            ->where('publish_date', '<=', Carbon::now());
        
        // 検索
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('body', 'like', "%{$request->search}%");
            });
        }
        
        $information = $query->orderBy('publish_date', 'desc')
            ->orderBy('sort', 'asc')
            ->paginate($request->get('per_page', 10));
        
        return InformationResource::collection($information)->additional([
            'pagination' => [
                'current_page' => $information->currentPage(),
                'last_page' => $information->lastPage(),
                'per_page' => $information->perPage(),
                'total' => $information->total(),
            ]
        ]);
    }
    
    /**
     * 情報詳細取得（公開API）
     */
    public function show($id)
    {
        $information = Information::findOrFail($id);
        
        // 公開中かつ公開日が過ぎているもののみ表示
        if ($information->status != 1 || $information->publish_date > Carbon::now()) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        return new InformationResource($information);
    }
}


