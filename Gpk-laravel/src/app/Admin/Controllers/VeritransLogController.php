<?php

namespace App\Admin\Controllers;

use App\Models\VeritransLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class VeritransLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Veritrans決済ログ';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new VeritransLog());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('reservation.id', '予約ID');
        $grid->column('order_id', '注文ID');
        $grid->column('type', 'タイプ')->using([
            1 => '予約',
            2 => '注文',
        ]);
        $grid->column('txn_status', '決済ステータス')->label([
            'SUCCESS' => 'success',
            'FAILURE' => 'danger',
            'PENDING' => 'warning',
        ]);
        $grid->column('txn_result_code', '結果コード');
        $grid->column('err_message', 'エラーメッセージ')->display(function ($msg) {
            return $msg ? \Str::limit($msg, 30) : '-';
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('created_at', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('user_id', 'ユーザー')->select(\App\Models\User::all()->pluck('name', 'id'));
            $filter->equal('txn_status', '決済ステータス')->select([
                'SUCCESS' => '成功',
                'FAILURE' => '失敗',
                'PENDING' => '処理中',
            ]);
            $filter->between('created_at', '作成日時')->datetime();
        });

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
        $show = new Show(VeritransLog::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('reservation.id', '予約ID');
        $show->field('order_id', '注文ID');
        $show->field('type', 'タイプ')->using([
            1 => '予約',
            2 => '注文',
        ]);
        $show->field('txn_status', '決済ステータス');
        $show->field('txn_result_code', '結果コード');
        $show->field('err_message', 'エラーメッセージ');
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
        $form = new Form(new VeritransLog());

        $form->select('user_id', 'ユーザー')
            ->options(\App\Models\User::all()->pluck('name', 'id'));
        $form->select('reservation_id', '予約')
            ->options(\App\Models\Reservation::all()->pluck('id', 'id'));
        $form->text('order_id', '注文ID');
        $form->select('type', 'タイプ')->options([
            1 => '予約',
            2 => '注文',
        ])->default(1);
        $form->text('txn_status', '決済ステータス');
        $form->text('txn_result_code', '結果コード');
        $form->textarea('err_message', 'エラーメッセージ');

        return $form;
    }
}
