<?php

namespace App\Admin\Controllers;

use App\Models\UserPointLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserPointLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ポイントログ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserPointLog());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('user.email', 'メールアドレス');
        $grid->column('point', 'ポイント')->display(function ($point) {
            $sign = $this->type == 1 ? '+' : '-';
            $color = $this->type == 1 ? 'green' : 'red';
            return '<span style="color: ' . $color . '">' . $sign . number_format($point) . 'ポイント</span>';
        });
        $grid->column('type', 'タイプ')->using([
            1 => '付与',
            2 => '利用',
        ])->label([
            1 => 'success',
            2 => 'warning',
        ]);
        $grid->column('reason', '理由');
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('user.name', 'ユーザー名');
            $filter->like('user.email', 'メールアドレス');
            $filter->equal('type', 'タイプ')->select([
                1 => '付与',
                2 => '利用',
            ]);
            $filter->like('reason', '理由');
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->with('user')->orderBy('created_at', 'desc');

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
        $show = new Show(UserPointLog::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('user.email', 'メールアドレス');
        $show->field('point', 'ポイント')->display(function ($point) {
            $sign = $this->type == 1 ? '+' : '-';
            return $sign . number_format($point) . 'ポイント';
        });
        $show->field('type', 'タイプ')->using([
            1 => '付与',
            2 => '利用',
        ]);
        $show->field('reason', '理由');
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
        $form = new Form(new UserPointLog());

        $form->select('user_id', 'ユーザー')->options(function ($id) {
            $user = \App\Models\User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users');
        $form->number('point', 'ポイント')->default(0);
        $form->select('type', 'タイプ')->options([
            1 => '付与',
            2 => '利用',
        ])->default(1);
        $form->text('reason', '理由');

        return $form;
    }
}

