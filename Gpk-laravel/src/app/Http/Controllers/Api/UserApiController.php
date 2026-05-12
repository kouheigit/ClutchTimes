<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    /**
     * ユーザー情報取得
     */
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }
    
    /**
     * ユーザー情報更新
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_kana' => 'nullable|string|max:255',
            'first_kana' => 'nullable|string|max:255',
            'zip1' => 'nullable|string|max:10',
            'zip2' => 'nullable|string|max:10',
            'address1' => 'nullable|string|max:500',
            'address2' => 'nullable|string|max:500',
            'tel' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // パスワードが入力されている場合のみ更新
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        return new UserResource($user->fresh());
    }
}




















