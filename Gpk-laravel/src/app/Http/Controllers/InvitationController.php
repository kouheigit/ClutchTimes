<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Reservation;
use App\Mail\InvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class InvitationController extends Controller
{
    /**
     * 招待一覧表示
     */
    public function index()
    {
        $user = Auth::user();
        
        // オーナーのみ招待を作成できる
        if ($user->type != \App\Consts\UserConst::TYPE_OWNER) {
            abort(403, 'オーナーのみ招待を作成できます');
        }
        
        $invitations = Invitation::where('owner_id', $user->id)
            ->with(['reservation', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('invitation.index', compact('invitations'));
    }
    
    /**
     * 招待作成画面
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        
        // オーナーのみ招待を作成できる
        if ($user->type != \App\Consts\UserConst::TYPE_OWNER) {
            abort(403, 'オーナーのみ招待を作成できます');
        }
        
        $reservation_id = $request->reservation_id;
        $reservation = null;
        
        if ($reservation_id) {
            $reservation = Reservation::where('owner_id', $user->id)
                ->findOrFail($reservation_id);
        }
        
        // ユーザーの予約一覧
        $reservations = Reservation::where('owner_id', $user->id)
            ->whereIn('status', [
                \App\Consts\ReservationConst::STATUS_UNDER_RESERVATION,
                \App\Consts\ReservationConst::STATUS_RESERVED
            ])
            ->orderBy('checkin_date', 'desc')
            ->get();
        
        return view('invitation.create', compact('reservation', 'reservations'));
    }
    
    /**
     * 招待作成
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // オーナーのみ招待を作成できる
        if ($user->type != \App\Consts\UserConst::TYPE_OWNER) {
            abort(403, 'オーナーのみ招待を作成できます');
        }
        
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);
        
        // 予約の所有権確認
        $reservation = Reservation::where('owner_id', $user->id)
            ->findOrFail($request->reservation_id);
        
        DB::beginTransaction();
        
        try {
            // 招待トークン生成
            $token = Str::random(64);
            
            // 招待作成
            $invitation = Invitation::create([
                'reservation_id' => $request->reservation_id,
                'owner_id' => $user->id,
                'token' => $token,
                'name' => $request->name,
                'email' => $request->email,
                'status' => 1, // 送信済み
            ]);
            
            DB::commit();
            
            // 招待メール送信
            try {
                Mail::to($request->email)->send(new InvitationMail($invitation));
            } catch (\Exception $e) {
                Log::warning('Invitation mail sending failed: ' . $e->getMessage());
                // メール送信失敗は招待作成を止めない
            }
            
            return redirect()->route('invitation.index')
                ->with('success', '招待を送信しました');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Invitation Error: ' . $e->getMessage());
            return back()->withErrors(['error' => '招待の作成に失敗しました: ' . $e->getMessage()]);
        }
    }
    
    /**
     * 招待削除
     */
    public function destroy(Invitation $invitation)
    {
        $user = Auth::user();
        
        if ($invitation->owner_id != $user->id) {
            abort(403);
        }
        
        $invitation->delete();
        
        return redirect()->route('invitation.index')
            ->with('success', '招待を削除しました');
    }
}

