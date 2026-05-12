<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '注文管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('reservation.id', '予約ID');
        $grid->column('service.title', 'サービス');
        $grid->column('quantity', '数量');
        $grid->column('total_price', '合計金額')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $grid->column('payment', '決済方法')->using([
            0 => '現地払い',
            1 => 'クレジット',
        ])->label([
            0 => 'warning',
            1 => 'success',
        ]);
        $grid->column('payment_status', '決済ステータス')->using([
            0 => '未決済',
            1 => '決済済',
        ])->label([
            0 => 'warning',
            1 => 'success',
        ]);
        $grid->column('type', 'タイプ')->using([
            1 => '事前予約',
            2 => '現地注文',
        ]);
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('user.name', 'ユーザー名');
            $filter->like('user.email', 'メールアドレス');
            $filter->equal('reservation_id', '予約ID');
            $filter->equal('payment', '決済方法')->select([
                0 => '現地払い',
                1 => 'クレジット',
            ]);
            $filter->equal('payment_status', '決済ステータス')->select([
                0 => '未決済',
                1 => '決済済',
            ]);
            $filter->equal('type', 'タイプ')->select([
                1 => '事前予約',
                2 => '現地注文',
            ]);
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->orderBy('created_at', 'desc');

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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('reservation.id', '予約ID');
        $show->field('service.title', 'サービス');
        $show->field('price', '単価')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $show->field('quantity', '数量');
        $show->field('total_price', '合計金額')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $show->field('payment', '決済方法')->using([
            0 => '現地払い',
            1 => 'クレジット',
        ]);
        $show->field('payment_status', '決済ステータス')->using([
            0 => '未決済',
            1 => '決済済',
        ]);
        $show->field('type', 'タイプ')->using([
            1 => '事前予約',
            2 => '現地注文',
        ]);
        $show->field('status', 'ステータス')->using([
            0 => '無効',
            1 => '有効',
        ]);
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');
        
        // 注文明細
        $show->orderDetails('注文明細', function ($orderDetails) {
            $orderDetails->setResource('/admin/order_details');
            $orderDetails->id();
            $orderDetails->column('service.title', 'サービス');
            $orderDetails->column('serviceOption.title', 'オプション');
            $orderDetails->quantity('数量');
            $orderDetails->column('price', '単価')->display(function ($price) {
                return '¥' . number_format($price);
            });
            $orderDetails->column('total_price', '合計')->display(function ($price) {
                return '¥' . number_format($price);
            });
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
        $form = new Form(new Order());

        // 基本情報
        $form->select('user_id', 'ユーザー')->options(function ($id) {
            $user = \App\Models\User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users')->required();
        
        $form->select('reservation_id', '予約')->options(function ($id) {
            $reservation = \App\Models\Reservation::find($id);
            if ($reservation) {
                return [$reservation->id => '予約 #' . $reservation->id];
            }
        })->ajax('/admin/api/reservations');
        
        $form->select('service_id', 'サービス')
            ->options(\App\Models\Service::all()->pluck('title', 'id'))
            ->required();
        
        // 価格・数量
        $form->currency('price', '単価')->symbol('¥')->default(0)->required();
        $form->number('quantity', '数量')->default(1)->min(1)->required();
        $form->currency('total_price', '合計金額')->symbol('¥')->default(0);
        
        // 決済情報
        $form->select('payment', '決済方法')->options([
            0 => '現地払い',
            1 => 'クレジット',
        ])->default(0);
        
        $form->select('payment_status', '決済ステータス')->options([
            0 => '未決済',
            1 => '決済済',
        ])->default(0);
        
        // タイプ・ステータス
        $form->select('type', 'タイプ')->options([
            1 => '事前予約',
            2 => '現地注文',
        ])->default(1);
        
        $form->select('status', 'ステータス')->options([
            0 => '無効',
            1 => '有効',
        ])->default(1);

        return $form;
    }
}
