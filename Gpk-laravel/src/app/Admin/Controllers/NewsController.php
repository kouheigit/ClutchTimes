<?php

namespace App\Admin\Controllers;

use App\Models\News;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NewsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'お知らせ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new News());

        $grid->column('id', 'ID')->sortable();
        $grid->column('title', 'タイトル');
        $grid->column('body', '内容')->display(function ($body) {
            return \Str::limit($body, 50);
        });
        $grid->column('publish_date', '公開日')->display(function ($date) {
            return date('Y/m/d', strtotime($date));
        });
        $grid->column('status', 'ステータス')->using([
            0 => '非公開',
            1 => '公開中',
        ])->dot([
            0 => 'danger',
            1 => 'success',
        ]);
        $grid->column('sort', '並び順')->sortable();
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('sort', 'asc')->orderBy('publish_date', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('title', 'タイトル');
            $filter->between('publish_date', '公開日')->date();
            $filter->equal('status', 'ステータス')->select([
                0 => '非公開',
                1 => '公開中',
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
        $show = new Show(News::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('title', 'タイトル');
        $show->field('body', '内容');
        $show->field('publish_date', '公開日');
        $show->field('status', 'ステータス')->using([
            0 => '非公開',
            1 => '公開中',
        ]);
        $show->field('sort', '並び順');
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
        $form = new Form(new News());

        $form->text('title', 'タイトル')->required();
        $form->textarea('body', '内容')->rows(10);
        $form->date('publish_date', '公開日')->default(date('Y-m-d'))->required();
        $form->switch('status', 'ステータス')->default(1);
        $form->number('sort', '並び順')->default(0);

        return $form;
    }
}
