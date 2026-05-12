<?php

namespace App\Admin\Controllers;

use App\Models\Holiday;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class HolidayController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '休日管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Holiday());

        $grid->column('id', 'ID')->sortable();
        $grid->column('date', '日付')->display(function ($date) {
            return date('Y/m/d', strtotime($date)) . ' (' . \Carbon\Carbon::parse($date)->locale('ja')->isoFormat('ddd') . ')';
        });
        $grid->column('name', '休日名');
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('date', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->between('date', '日付')->date();
            $filter->like('name', '休日名');
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
        $show = new Show(Holiday::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('date', '日付');
        $show->field('name', '休日名');
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
        $form = new Form(new Holiday());

        $form->date('date', '日付')->required();
        $form->text('name', '休日名')->required();

        return $form;
    }
}
