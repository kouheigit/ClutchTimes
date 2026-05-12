<?php

namespace App\Admin\Controllers;

use App\Models\Calendar;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CalendarController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'カレンダー管理（FIXDAY）';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Calendar());

        $grid->column('id', 'ID')->sortable();
        $grid->column('hotel.name', 'ホテル');
        $grid->column('user.name', 'ユーザー');
        $grid->column('start_date', '開始日')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('end_date', '終了日')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('status', 'ステータス')->using([
            1 => '予約可能',
            2 => '予約中',
            3 => '予約確定',
        ])->label([
            1 => 'success',
            2 => 'warning',
            3 => 'info',
        ]);
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('start_date', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('hotel_id', 'ホテル')->select(\App\Models\Hotel::all()->pluck('name', 'id'));
            $filter->between('start_date', '開始日')->date();
            $filter->equal('status', 'ステータス')->select([
                1 => '予約可能',
                2 => '予約中',
                3 => '予約確定',
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
        $show = new Show(Calendar::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('hotel.name', 'ホテル');
        $show->field('user.name', 'ユーザー');
        $show->field('start_date', '開始日');
        $show->field('end_date', '終了日');
        $show->field('status', 'ステータス')->using([
            1 => '予約可能',
            2 => '予約中',
            3 => '予約確定',
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
        $form = new Form(new Calendar());

        $form->select('hotel_id', 'ホテル')
            ->options(\App\Models\Hotel::all()->pluck('name', 'id'))
            ->required();
        $form->select('user_id', 'ユーザー')
            ->options(\App\Models\User::all()->pluck('name', 'id'));
        $form->date('start_date', '開始日')->required();
        $form->date('end_date', '終了日')->required();
        $form->select('status', 'ステータス')->options([
            1 => '予約可能',
            2 => '予約中',
            3 => '予約確定',
        ])->default(1);

        return $form;
    }
}
