<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * お知らせ一覧表示
     */
    public function index()
    {
        $news = News::where('status', 1)
            ->where('publish_date', '<=', Carbon::now())
            ->orderBy('publish_date', 'desc')
            ->orderBy('sort', 'asc')
            ->paginate(10);
        
        return view('news.index', compact('news'));
    }
    
    /**
     * お知らせ詳細表示
     */
    public function show(News $news)
    {
        // 公開中かつ公開日が過ぎているもののみ表示
        if ($news->status != 1 || $news->publish_date > Carbon::now()) {
            abort(404);
        }
        
        return view('news.show', compact('news'));
    }
}

