<?php

namespace App\Admin\Controllers;

use App\Models\ReservationLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReservationLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '予約ログ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ReservationLog());

        $grid->column('id', 'ID')->sortable();
        $grid->column('reservation.id', '予約ID');
        $grid->column('user.name', 'ユーザー');
        $grid->column('action', 'アクション');
        $grid->column('data', 'データ')->display(function ($data) {
            if ($data) {
                return '<pre>' . json_encode(json_decode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            }
            return '-';
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('reservation_id', '予約ID');
            $filter->like('user.name', 'ユーザー名');
            $filter->like('action', 'アクション');
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->with(['reservation', 'user'])->orderBy('created_at', 'desc');

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
        $show = new Show(ReservationLog::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('reservation.id', '予約ID');
        $show->field('user.name', 'ユーザー');
        $show->field('action', 'アクション');
        $show->field('data', 'データ')->unescape()->as(function ($data) {
            if ($data) {
                return '<pre>' . json_encode(json_decode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            }
            return '-';
        });
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ReservationLog());

        $form->select('reservation_id', '予約')->options(function ($id) {
            $reservation = \App\Models\Reservation::find($id);
            if ($reservation) {
                return [$reservation->id => '予約 #' . $reservation->id];
            }
        })->ajax('/admin/api/reservations');
        $form->select('user_id', 'ユーザー')->options(function ($id) {
            $user = \App\Models\User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users');
        $form->text('action', 'アクション');
        $form->textarea('data', 'データ');

        return $form;
    }
}

