<?php

namespace App\Admin\Controllers;

use App\Models\MailTemplate;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class MailTemplateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'メールテンプレート管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MailTemplate());

        $grid->column('id', 'ID')->sortable();
        $grid->column('type', 'タイプ');
        $grid->column('subject', '件名');
        $grid->column('body', '本文')->display(function ($body) {
            return Str::limit($body, 50);
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
        $grid->column('updated_at', '更新日時')->display(function ($updated_at) {
            return date('Y-m-d H:i', strtotime($updated_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('type', 'タイプ');
            $filter->like('subject', '件名');
            $filter->equal('status', 'ステータス')->select([
                0 => '無効',
                1 => '有効',
            ]);
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->orderBy('type', 'asc')->orderBy('created_at', 'desc');

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
        $show = new Show(MailTemplate::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('type', 'タイプ');
        $show->field('subject', '件名');
        $show->field('body', '本文')->unescape();
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
        $form = new Form(new MailTemplate());

        $form->text('type', 'タイプ')->required();
        $form->text('subject', '件名')->required();
        $form->textarea('body', '本文')->required();
        $form->select('status', 'ステータス')->options([
            0 => '無効',
            1 => '有効',
        ])->default(1);

        return $form;
    }
}

