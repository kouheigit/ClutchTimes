<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * 設定一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('settings.index', compact('user'));
    }
    
    /**
     * プロフィール設定表示
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('settings.profile', compact('user'));
    }
    
    /**
     * プロフィール更新
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        $user->update($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'プロフィールを更新しました');
    }
    
    /**
     * パスワード設定表示
     */
    public function password()
    {
        return view('settings.password');
    }
    
    /**
     * パスワード更新
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが正しくありません']);
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('settings.index')
            ->with('success', 'パスワードを更新しました');
    }
}

