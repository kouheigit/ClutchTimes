<?php

namespace App\Admin\Controllers;

use App\Models\Freeday;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FreedayController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'FREEDAY管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Freeday());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('freedays', '利用可能日数')->display(function ($days) {
            return $days . '泊';
        });
        $grid->column('start_date', '利用開始日')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('end_date', '有効期限')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('status', 'ステータス')->using([
            0 => '無効',
            1 => '有効',
        ])->dot([
            0 => 'danger',
            1 => 'success',
        ]);
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('end_date', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('user_id', 'ユーザー')->select(\App\Models\User::all()->pluck('name', 'id'));
            $filter->between('end_date', '有効期限')->date();
            $filter->equal('status', 'ステータス')->select([
                0 => '無効',
                1 => '有効',
            ]);
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
        $show = new Show(Freeday::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('freedays', '利用可能日数');
        $show->field('start_date', '利用開始日');
        $show->field('end_date', '有効期限');
        $show->field('status', 'ステータス')->using([
            0 => '無効',
            1 => '有効',
        ]);
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
        $form = new Form(new Freeday());

        $form->select('user_id', 'ユーザー')
            ->options(\App\Models\User::all()->pluck('name', 'id'))
            ->required();
        $form->number('freedays', '利用可能日数')->default(1)->required();
        $form->date('start_date', '利用開始日')->required();
        $form->date('end_date', '有効期限')->required();
        $form->switch('status', 'ステータス')->default(1);

        return $form;
    }
}
