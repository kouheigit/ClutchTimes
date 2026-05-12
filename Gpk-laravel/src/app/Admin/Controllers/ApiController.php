<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Calendar;
use App\Models\Cart;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\AddOrder;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * ユーザー検索API
     * GET /admin/api/users?q=検索クエリ
     */
    public function users(Request $request)
    {
        $q = $request->get('q');
        
        $query = User::query();
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('member_id', 'like', "%{$q}%");
            });
        }
        
        $users = $query->limit(20)->get();
        
        return response()->json(
            $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . ' (' . $user->email . ')',
                ];
            })
        );
    }

    /**
     * 予約検索API
     * GET /admin/api/reservations?q=検索クエリ
     */
    public function reservations(Request $request)
    {
        $q = $request->get('q');
        
        $query = Reservation::with('user', 'hotel');
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }
        
        $reservations = $query->limit(20)->get();
        
        return response()->json(
            $reservations->map(function ($reservation) {
                $userName = $reservation->user ? $reservation->user->name : '-';
                $hotelName = $reservation->hotel ? $reservation->hotel->name : '-';
                return [
                    'id' => $reservation->id,
                    'text' => '予約 #' . $reservation->id . ' - ' . $userName . ' (' . $hotelName . ')',
                ];
            })
        );
    }

    /**
     * カレンダー検索API
     * GET /admin/api/calendars?q=検索クエリ
     */
    public function calendars(Request $request)
    {
        $q = $request->get('q');
        
        $query = Calendar::with('hotel', 'user');
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhereHas('hotel', function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('user', function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%");
                    });
            });
        }
        
        $calendars = $query->limit(20)->get();
        
        return response()->json(
            $calendars->map(function ($calendar) {
                $hotelName = $calendar->hotel ? $calendar->hotel->name : '-';
                $userName = $calendar->user ? $calendar->user->name : '-';
                $startDate = $calendar->start_date ? date('Y/m/d', strtotime($calendar->start_date)) : '-';
                return [
                    'id' => $calendar->id,
                    'text' => 'カレンダー #' . $calendar->id . ' - ' . $hotelName . ' (' . $userName . ') [' . $startDate . ']',
                ];
            })
        );
    }

    /**
     * カート検索API
     * GET /admin/api/carts?q=検索クエリ
     */
    public function carts(Request $request)
    {
        $q = $request->get('q');
        
        $query = Cart::with('user');
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    });
            });
        }
        
        $carts = $query->limit(20)->get();
        
        return response()->json(
            $carts->map(function ($cart) {
                $userName = $cart->user ? $cart->user->name : '-';
                $userEmail = $cart->user ? $cart->user->email : '-';
                return [
                    'id' => $cart->id,
                    'text' => 'カート #' . $cart->id . ' - ' . $userName . ' (' . $userEmail . ')',
                ];
            })
        );
    }

    /**
     * サービス検索API
     * GET /admin/api/services?q=検索クエリ
     */
    public function services(Request $request)
    {
        $q = $request->get('q');
        
        $query = Service::with('hotel');
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('id', 'like', "%{$q}%")
                    ->orWhereHas('hotel', function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%");
                    });
            });
        }
        
        $services = $query->limit(20)->get();
        
        return response()->json(
            $services->map(function ($service) {
                $hotelName = $service->hotel ? $service->hotel->name : '-';
                return [
                    'id' => $service->id,
                    'text' => $service->title . ' (' . $hotelName . ')',
                ];
            })
        );
    }

    /**
     * サービスオプション検索API
     * GET /admin/api/service_options?q=検索クエリ
     */
    public function serviceOptions(Request $request)
    {
        $q = $request->get('q');
        
        $query = ServiceOption::with('service');
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                    ->orWhere('id', 'like', "%{$q}%")
                    ->orWhereHas('service', function ($query) use ($q) {
                        $query->where('title', 'like', "%{$q}%");
                    });
            });
        }
        
        $serviceOptions = $query->limit(20)->get();
        
        return response()->json(
            $serviceOptions->map(function ($serviceOption) {
                $serviceTitle = $serviceOption->service ? $serviceOption->service->title : '-';
                return [
                    'id' => $serviceOption->id,
                    'text' => $serviceOption->title . ' (' . $serviceTitle . ')',
                ];
            })
        );
    }

    /**
     * 追加注文検索API
     * GET /admin/api/add_orders?q=検索クエリ
     */
    public function addOrders(Request $request)
    {
        $q = $request->get('q');
        
        $query = AddOrder::with('user', 'reservation');
        
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('reservation', function ($query) use ($q) {
                        $query->where('id', 'like', "%{$q}%");
                    });
            });
        }
        
        $addOrders = $query->limit(20)->get();
        
        return response()->json(
            $addOrders->map(function ($addOrder) {
                $userName = $addOrder->user ? $addOrder->user->name : '-';
                $reservationId = $addOrder->reservation ? $addOrder->reservation->id : '-';
                return [
                    'id' => $addOrder->id,
                    'text' => '追加注文 #' . $addOrder->id . ' - ' . $userName . ' (予約 #' . $reservationId . ')',
                ];
            })
        );
    }
}

