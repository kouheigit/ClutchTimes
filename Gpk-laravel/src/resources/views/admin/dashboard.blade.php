<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">総ユーザー数</span>
                <span class="info-box-number">{{ number_format($stats['total_users']) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">総予約数</span>
                <span class="info-box-number">{{ number_format($stats['total_reservations']) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">申込中</span>
                <span class="info-box-number">{{ number_format($stats['pending_reservations']) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">予約確定</span>
                <span class="info-box-number">{{ number_format($stats['confirmed_reservations']) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="fa fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">総注文数</span>
                <span class="info-box-number">{{ number_format($stats['total_orders']) }}</span>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-purple"><i class="fa fa-calendar-check-o"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">予約可能カレンダー</span>
                <span class="info-box-number">{{ number_format($stats['available_calendars']) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">最近の予約</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ユーザー</th>
                            <th>ホテル</th>
                            <th>チェックイン</th>
                            <th>チェックアウト</th>
                            <th>ステータス</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $recent_reservations = \App\Models\Reservation::with(['user', 'hotel'])
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();
                        @endphp
                        @forelse($recent_reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>{{ $reservation->user->name ?? '-' }}</td>
                            <td>{{ $reservation->hotel->name ?? '-' }}</td>
                            <td>{{ $reservation->checkin_date->format('Y/m/d') }}</td>
                            <td>{{ $reservation->checkout_date->format('Y/m/d') }}</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        1 => '<span class="label label-info">申込中</span>',
                                        2 => '<span class="label label-warning">予約中</span>',
                                        3 => '<span class="label label-success">予約確定</span>',
                                        4 => '<span class="label label-primary">チェックイン済</span>',
                                        5 => '<span class="label label-default">チェックアウト済</span>',
                                        8 => '<span class="label label-warning">キャンセル中</span>',
                                        9 => '<span class="label label-danger">キャンセル</span>',
                                    ];
                                    echo $statusLabels[$reservation->status] ?? '<span class="label">-</span>';
                                @endphp
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">予約がありません</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

