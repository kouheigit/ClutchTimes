<?php

namespace App\Admin\Controllers;

use App\Models\Service;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ServiceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'サービス管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Service());

        $grid->column('id', 'ID')->sortable();
        $grid->column('hotel.name', 'ホテル');
        $grid->column('title', 'タイトル');
        $grid->column('price', '価格')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $grid->column('stock', '在庫');
        $grid->column('unit', '単位');
        $grid->column('tab', 'タブ')->using([
            1 => '事前予約',
            2 => '現地注文',
        ]);
        $grid->column('status', 'ステータス')->using([
            0 => '無効',
            1 => '有効',
        ])->dot([
            0 => 'danger',
            1 => 'success',
        ]);
        $grid->column('created_at', '登録日')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('title', 'タイトル');
            $filter->equal('hotel_id', 'ホテル')->select(\App\Models\Hotel::all()->pluck('name', 'id'));
            $filter->equal('tab', 'タブ')->select([
                1 => '事前予約',
                2 => '現地注文',
            ]);
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
        $show = new Show(Service::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('hotel.name', 'ホテル');
        $show->field('title', 'タイトル');
        $show->field('body', '説明');
        $show->field('price', '価格')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $show->field('stock', '在庫');
        $show->field('minimum', '最小数量');
        $show->field('unit', '単位');
        $show->field('tab', 'タブ')->using([
            1 => '事前予約',
            2 => '現地注文',
        ]);
        $show->field('sort', '並び順');
        $show->field('image', '画像')->image();
        $show->field('status', 'ステータス')->using([
            0 => '無効',
            1 => '有効',
        ]);
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');
        
        // サービスオプション
        $show->serviceOptions('サービスオプション', function ($serviceOptions) {
            $serviceOptions->setResource('/admin/service_options');
            $serviceOptions->id();
            $serviceOptions->title('タイトル');
            $serviceOptions->column('price', '価格')->display(function ($price) {
                return '¥' . number_format($price);
            });
            $serviceOptions->sort('並び順');
            $serviceOptions->status('ステータス')->using([
                0 => '無効',
                1 => '有効',
            ]);
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Service());

        $form->select('hotel_id', 'ホテル')
            ->options(\App\Models\Hotel::all()->pluck('name', 'id'))
            ->required();
        $form->text('title', 'タイトル')->required();
        $form->textarea('body', '説明');
        $form->currency('price', '価格')->symbol('¥')->required();
        $form->number('stock', '在庫')->default(0);
        $form->number('minimum', '最小数量')->default(1);
        $form->text('unit', '単位')->default('個');
        $form->number('tab', 'タブ')->default(1);
        $form->number('sort', '並び順')->default(0);
        $form->image('image', '画像');
        $form->switch('status', 'ステータス')->default(1);

        return $form;
    }
}
