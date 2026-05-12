<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Http\Resources\ReservationResource;
use App\Consts\ReservationConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationApiController extends Controller
{
    /**
     * 予約一覧取得
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Reservation::where(function($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('owner_id', $user->id);
        })
        ->with(['hotel', 'user', 'orders.orderDetails.service', 'orders.orderDetails.serviceOption'])
        ->orderBy('checkin_date', 'desc');
        
        // ステータスフィルター
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // 日付範囲フィルター
        if ($request->has('from_date')) {
            $query->where('checkin_date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date')) {
            $query->where('checkout_date', '<=', $request->to_date);
        }
        
        $reservations = $query->paginate($request->get('per_page', 20));
        
        return ReservationResource::collection($reservations);
    }
    
    /**
     * 予約詳細取得
     */
    public function show(Request $request, $id)
    {
        $reservation = Reservation::with([
            'hotel', 
            'user', 
            'orders.orderDetails.service', 
            'orders.orderDetails.serviceOption',
            'addOrders.addOrderDetails.service',
            'addOrders.addOrderDetails.serviceOption'
        ])->findOrFail($id);
        
        // 権限チェック
        if ($reservation->user_id != $request->user()->id && $reservation->owner_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return new ReservationResource($reservation);
    }
    
    /**
     * 予約作成
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'calendar_id' => 'nullable|exists:calendars,id',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'adult' => 'required|integer|min:1|max:10',
            'child' => 'nullable|integer|min:0|max:10',
            'dog' => 'nullable|integer|min:0|max:5',
            'note' => 'nullable|string|max:500',
            'payment' => 'nullable|integer|in:0,1',
        ]);
        
        DB::beginTransaction();
        
        try {
            $user = $request->user();
            
            // 宿泊日数を計算
            $checkin_date = \Carbon\Carbon::parse($validated['checkin_date']);
            $checkout_date = \Carbon\Carbon::parse($validated['checkout_date']);
            $days = $checkin_date->diffInDays($checkout_date);
            
            // 予約作成
            $reservation = Reservation::create([
                'hotel_id' => $validated['hotel_id'],
                'user_id' => $user->id,
                'owner_id' => $user->type == 2 ? $user->id : ($user->user_id ?? $user->id),
                'calendar_id' => $validated['calendar_id'] ?? null,
                'checkin_date' => $validated['checkin_date'],
                'checkout_date' => $validated['checkout_date'],
                'days' => $days,
                'adult' => $validated['adult'],
                'child' => $validated['child'] ?? 0,
                'dog' => $validated['dog'] ?? 0,
                'name' => $user->name,
                'note' => $validated['note'] ?? null,
                'payment' => $validated['payment'] ?? 0,
                'status' => ReservationConst::STATUS_UNDER_RESERVATION,
            ]);
            
            DB::commit();
            
            return new ReservationResource($reservation->load(['hotel', 'user']));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation API Error: ' . $e->getMessage());
            return response()->json(['error' => '予約の作成に失敗しました'], 500);
        }
    }
    
    /**
     * 予約更新
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // 権限チェック
        if ($reservation->user_id != $request->user()->id && $reservation->owner_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'checkin_date' => 'sometimes|date',
            'checkout_date' => 'sometimes|date|after:checkin_date',
            'adult' => 'sometimes|integer|min:1|max:10',
            'child' => 'nullable|integer|min:0|max:10',
            'dog' => 'nullable|integer|min:0|max:5',
            'note' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // 宿泊日数を再計算
            if (isset($validated['checkin_date']) || isset($validated['checkout_date'])) {
                $checkin_date = \Carbon\Carbon::parse($validated['checkin_date'] ?? $reservation->checkin_date);
                $checkout_date = \Carbon\Carbon::parse($validated['checkout_date'] ?? $reservation->checkout_date);
                $validated['days'] = $checkin_date->diffInDays($checkout_date);
            }
            
            $reservation->update($validated);
            
            DB::commit();
            
            return new ReservationResource($reservation->load(['hotel', 'user']));
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation Update API Error: ' . $e->getMessage());
            return response()->json(['error' => '予約の更新に失敗しました'], 500);
        }
    }
    
    /**
     * 予約キャンセル
     */
    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // 権限チェック
        if ($reservation->user_id != $request->user()->id && $reservation->owner_id != $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        DB::beginTransaction();
        
        try {
            $reservation->update(['status' => ReservationConst::STATUS_CANCEL]);
            
            // カレンダー解放
            if ($reservation->calendar_id) {
                \App\Models\Calendar::where('id', $reservation->calendar_id)
                    ->update(['status' => 1]);
            }
            
            DB::commit();
            
            return response()->json([
                'message' => '予約をキャンセルしました',
                'data' => new ReservationResource($reservation->load(['hotel', 'user']))
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reservation Cancel API Error: ' . $e->getMessage());
            return response()->json(['error' => '予約のキャンセルに失敗しました'], 500);
        }
    }
}




















