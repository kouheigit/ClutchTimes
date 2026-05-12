<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * プロフィール表示
     */
    public function show()
    {
        $user = Auth::user();
        
        return view('profile.show', compact('user'));
    }
    
    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = Auth::user();
        
        return view('profile.edit', compact('user'));
    }
    
    /**
     * プロフィール更新
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('profile.show')
            ->with('success', 'プロフィールを更新しました');
    }
}

