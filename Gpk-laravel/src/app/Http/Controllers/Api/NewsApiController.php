<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NewsApiController extends Controller
{
    /**
     * お知らせ一覧取得（公開API）
     */
    public function index(Request $request)
    {
        $query = News::where('status', 1)
            ->where('publish_date', '<=', Carbon::now());
        
        // 検索
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('body', 'like', "%{$request->search}%");
            });
        }
        
        $news = $query->orderBy('publish_date', 'desc')
            ->orderBy('sort', 'asc')
            ->paginate($request->get('per_page', 10));
        
        return NewsResource::collection($news)->additional([
            'pagination' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ]
        ]);
    }
    
    /**
     * お知らせ詳細取得（公開API）
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        
        // 公開中かつ公開日が過ぎているもののみ表示
        if ($news->status != 1 || $news->publish_date > Carbon::now()) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        return new NewsResource($news);
    }
}


