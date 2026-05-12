<?php

namespace App\Admin\Controllers;

use App\Models\Reservation;
use App\Consts\ReservationConst;
use App\Admin\Exports\ReservationExporter;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReservationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '予約管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Reservation());
        
        // カラム設定
        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('hotel.name', 'ホテル');
        $grid->column('checkin_date', 'チェックイン')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('checkout_date', 'チェックアウト')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('days', '泊数')->sortable();
        $grid->column('adult', '大人')->sortable();
        $grid->column('child', '子供');
        $grid->column('dog', '犬');
        $grid->column('payment', '決済')->using([
            0 => '現地払い',
            1 => 'クレジット',
        ])->label([
            0 => 'warning',
            1 => 'success',
        ]);
        $grid->column('status', 'ステータス')->using(ReservationConst::STATUS_LIST)->label([
            1 => 'info',
            2 => 'warning',
            3 => 'success',
            4 => 'primary',
            5 => 'default',
            8 => 'warning',
            9 => 'danger',
        ]);
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y/m/d H:i', strtotime($created_at));
        });
        
        // デフォルトソート
        $grid->model()->orderBy('checkin_date', 'desc');
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            
            // ユーザー検索
            $filter->like('user.name', 'ユーザー名');
            $filter->like('user.email', 'メールアドレス');
            
            // 日付検索
            $filter->between('checkin_date', 'チェックイン日')->date();
            $filter->between('checkout_date', 'チェックアウト日')->date();
            
            // ステータス検索
            $filter->equal('status', 'ステータス')->select(ReservationConst::STATUS_LIST);
            
            // ホテル検索
            $filter->equal('hotel_id', 'ホテル')->select(\App\Models\Hotel::all()->pluck('name', 'id'));
            
            // 決済方法
            $filter->equal('payment', '決済方法')->select([
                0 => '現地払い',
                1 => 'クレジット',
            ]);
        });
        
        // エクスポート
        $grid->exporter(new ReservationExporter());
        
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Reservation::findOrFail($id));
        
        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('owner.name', 'オーナー');
        $show->field('hotel.name', 'ホテル');
        $show->field('checkin_date', 'チェックイン日');
        $show->field('checkout_date', 'チェックアウト日');
        $show->field('checkin_time', 'チェックイン時刻');
        $show->field('checkout_time', 'チェックアウト時刻');
        $show->field('days', '宿泊日数');
        $show->field('name', '代表者名');
        $show->field('adult', '大人人数');
        $show->field('child', '子供人数');
        $show->field('dog', '犬頭数');
        $show->field('note', '備考');
        $show->field('room_key', '入室番号');
        $show->field('payment', '決済方法')->using([0 => '現地払い', 1 => 'クレジット']);
        $show->field('status', 'ステータス')->using(ReservationConst::STATUS_LIST);
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');
        
        // 関連サービス注文
        $show->orders('サービス注文', function ($orders) {
            $orders->setResource('/admin/orders');
            $orders->id();
            $orders->column('service.title', 'サービス');
            $orders->quantity();
            $orders->total_price();
            $orders->payment_status();
        });
        
        // 追加注文
        $show->addOrders('追加注文', function ($addOrders) {
            $addOrders->setResource('/admin/add_orders');
            $addOrders->id();
            $addOrders->column('total_price', '合計金額');
            $addOrders->column('payment', '決済方法')->using([0 => '現地払い', 1 => 'クレジット']);
            $addOrders->column('payment_status', '支払い状況')->using([0 => '未払い', 1 => '支払済み']);
            $addOrders->column('created_at', '作成日時');
        });
        
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Reservation());
        
        // 基本情報
        $form->select('user_id', 'ユーザー')
            ->options(\App\Models\User::all()->pluck('name', 'id'))
            ->required();
        $form->select('hotel_id', 'ホテル')
            ->options(\App\Models\Hotel::all()->pluck('name', 'id'))
            ->required();
        
        // 日程
        $form->date('checkin_date', 'チェックイン日')->required();
        $form->date('checkout_date', 'チェックアウト日')->required();
        $form->time('checkin_time', 'チェックイン時刻');
        $form->time('checkout_time', 'チェックアウト時刻');
        $form->number('days', '宿泊日数')->default(1);
        
        // ゲスト情報
        $form->text('name', '代表者名');
        $form->number('adult', '大人人数')->default(0);
        $form->number('child', '子供人数')->default(0);
        $form->number('dog', '犬頭数')->default(0);
        $form->textarea('note', '備考');
        
        // 施設情報
        $form->text('room_key', '入室番号');
        
        // 決済・ステータス
        $form->select('payment', '決済方法')->options([
            0 => '現地払い',
            1 => 'クレジット',
        ])->default(0);
        $form->select('status', 'ステータス')
            ->options(ReservationConst::STATUS_LIST)
            ->default(1);
        
        return $form;
    }
}
