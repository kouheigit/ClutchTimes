<?php

namespace App\Admin\Controllers;

use App\Models\CalendarOption;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CalendarOptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'カレンダーオプション管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CalendarOption());

        $grid->column('id', 'ID')->sortable();
        $grid->column('calendar.id', 'カレンダーID');
        $grid->column('title', 'タイトル');
        $grid->column('body', '内容')->display(function ($body) {
            return \Str::limit($body, 50);
        });
        $grid->column('sort', '並び順')->sortable();
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
        
        $grid->model()->orderBy('sort', 'asc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('calendar_id', 'カレンダーID');
            $filter->like('title', 'タイトル');
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
        $show = new Show(CalendarOption::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('calendar.id', 'カレンダーID');
        $show->field('title', 'タイトル');
        $show->field('body', '内容');
        $show->field('sort', '並び順');
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
        $form = new Form(new CalendarOption());

        $form->select('calendar_id', 'カレンダー')
            ->options(\App\Models\Calendar::all()->pluck('id', 'id'))
            ->required();
        $form->text('title', 'タイトル')->required();
        $form->textarea('body', '内容')->rows(5);
        $form->number('sort', '並び順')->default(0);
        $form->switch('status', 'ステータス')->default(1);

        return $form;
    }
}
