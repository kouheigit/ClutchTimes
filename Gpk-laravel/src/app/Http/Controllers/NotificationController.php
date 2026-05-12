<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * 通知一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // 通知機能は将来的に実装
        // 現在は空の配列を返す
        $notifications = [];
        
        return view('notifications.index', compact('notifications'));
    }
    
    /**
     * 通知詳細表示
     */
    public function show($id)
    {
        // 通知機能は将来的に実装
        return view('notifications.show');
    }
    
    /**
     * 通知を既読にする
     */
    public function markAsRead($id)
    {
        // 通知機能は将来的に実装
        return redirect()->route('notifications.index')
            ->with('success', '通知を既読にしました');
    }
}

