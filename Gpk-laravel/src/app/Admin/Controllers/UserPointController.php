<?php

namespace App\Admin\Controllers;

use App\Models\UserPoint;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserPointController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ユーザーポイント管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserPoint());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('user.email', 'メールアドレス');
        $grid->column('point', 'ポイント')->display(function ($point) {
            return number_format($point) . 'ポイント';
        });
        $grid->column('from', '有効開始日')->display(function ($from) {
            return $from ? date('Y-m-d', strtotime($from)) : '-';
        });
        $grid->column('to', '有効期限')->display(function ($to) {
            return $to ? date('Y-m-d', strtotime($to)) : '-';
        });
        $grid->column('is_expired', '有効期限切れ')->display(function () {
            if ($this->to && strtotime($this->to) < time()) {
                return '<span class="label label-danger">期限切れ</span>';
            }
            return '<span class="label label-success">有効</span>';
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('user.name', 'ユーザー名');
            $filter->like('user.email', 'メールアドレス');
            $filter->between('from', '有効開始日')->date();
            $filter->between('to', '有効期限')->date();
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
        $show = new Show(UserPoint::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('user.email', 'メールアドレス');
        $show->field('point', 'ポイント')->display(function ($point) {
            return number_format($point) . 'ポイント';
        });
        $show->field('from', '有効開始日')->display(function ($from) {
            return $from ? date('Y-m-d', strtotime($from)) : '-';
        });
        $show->field('to', '有効期限')->display(function ($to) {
            return $to ? date('Y-m-d', strtotime($to)) : '-';
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
        $form = new Form(new UserPoint());

        $form->select('user_id', 'ユーザー')->options(function ($id) {
            $user = \App\Models\User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users');
        $form->number('point', 'ポイント')->default(0);
        $form->date('from', '有効開始日');
        $form->date('to', '有効期限');

        return $form;
    }
}

