<?php

namespace App\Admin\Controllers;

use App\Models\OrderDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '注文明細管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderDetail());

        $grid->column('id', 'ID')->sortable();
        $grid->column('order.id', '注文ID');
        $grid->column('service.title', 'サービス');
        $grid->column('serviceOption.title', 'オプション');
        $grid->column('price', '単価')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $grid->column('quantity', '数量');
        $grid->column('total_price', '合計金額')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('created_at', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('order_id', '注文ID');
            $filter->equal('service_id', 'サービス')->select(\App\Models\Service::all()->pluck('title', 'id'));
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
        $show = new Show(OrderDetail::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('order.id', '注文ID');
        $show->field('service.title', 'サービス');
        $show->field('serviceOption.title', 'オプション');
        $show->field('price', '単価');
        $show->field('quantity', '数量');
        $show->field('total_price', '合計金額');
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
        $form = new Form(new OrderDetail());

        $form->select('order_id', '注文')
            ->options(\App\Models\Order::all()->pluck('id', 'id'))
            ->required();
        $form->select('service_id', 'サービス')
            ->options(\App\Models\Service::all()->pluck('title', 'id'))
            ->required();
        $form->select('service_option_id', 'オプション')
            ->options(\App\Models\ServiceOption::all()->pluck('title', 'id'));
        $form->currency('price', '単価')->symbol('¥')->default(0);
        $form->number('quantity', '数量')->default(1)->required();
        $form->currency('total_price', '合計金額')->symbol('¥');

        return $form;
    }
}
