<?php

namespace App\Http\Controllers\Invitation;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * 招待URL表示
     */
    public function show($token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 1)
            ->firstOrFail();
        
        // 招待元の予約情報
        $reservation = Reservation::with(['hotel', 'owner'])
            ->findOrFail($invitation->reservation_id);
        
        return view('invitation.register', compact('invitation', 'reservation'));
    }
    
    /**
     * ゲストユーザー登録
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|exists:invitations,token',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'tel' => 'required|string|max:20',
        ]);
        
        $invitation = Invitation::where('token', $request->token)
            ->where('status', 1)
            ->firstOrFail();
        
        DB::beginTransaction();
        
        try {
            // ゲストユーザー作成
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'tel' => $request->tel,
                'type' => \App\Consts\UserConst::TYPE_GENERAL, // 一般ユーザー
                'user_id' => $invitation->owner_id, // オーナーと紐付け
                'status' => 1,
            ]);
            
            // 招待情報更新
            $invitation->update([
                'user_id' => $user->id,
                'status' => 2, // 登録済み
            ]);
            
            // 予約にゲストを紐付け（コピーを作成する場合）
            // ここでは既存の予約は変更せず、新しい予約を作成するか、またはそのままにする
            // ビジネスロジックに応じて調整が必要
            
            DB::commit();
            
            // 自動ログイン
            Auth::login($user);
            
            return redirect()->route('invitation.complete')
                ->with('success', '登録が完了しました');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invitation Registration Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '登録に失敗しました: ' . $e->getMessage()]);
        }
    }
}

