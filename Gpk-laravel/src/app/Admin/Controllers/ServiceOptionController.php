<?php

namespace App\Admin\Controllers;

use App\Models\ServiceOption;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ServiceOptionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'サービスオプション管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ServiceOption());

        $grid->column('id', 'ID')->sortable();
        $grid->column('service.title', 'サービス');
        $grid->column('title', 'オプション名');
        $grid->column('price', '価格')->display(function ($price) {
            return '¥' . number_format($price);
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
            $filter->equal('service_id', 'サービス')->select(\App\Models\Service::all()->pluck('title', 'id'));
            $filter->like('title', 'オプション名');
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
        $show = new Show(ServiceOption::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('service.title', 'サービス');
        $show->field('title', 'オプション名');
        $show->field('price', '価格');
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
        $form = new Form(new ServiceOption());

        $form->select('service_id', 'サービス')
            ->options(\App\Models\Service::all()->pluck('title', 'id'))
            ->required();
        $form->text('title', 'オプション名')->required();
        $form->currency('price', '価格')->symbol('¥')->default(0);
        $form->number('sort', '並び順')->default(0);
        $form->switch('status', 'ステータス')->default(1);

        return $form;
    }
}
