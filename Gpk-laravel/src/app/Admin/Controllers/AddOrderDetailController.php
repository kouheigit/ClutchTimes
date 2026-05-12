<?php

namespace App\Admin\Controllers;

use App\Models\AddOrderDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AddOrderDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '追加注文明細管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AddOrderDetail());

        $grid->column('id', 'ID')->sortable();
        $grid->column('addOrder.id', '追加注文ID');
        $grid->column('service.title', 'サービス');
        $grid->column('serviceOption.title', 'オプション');
        $grid->column('quantity', '数量');
        $grid->column('price', '単価')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $grid->column('total_price', '合計')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('add_order_id', '追加注文ID');
            $filter->equal('service_id', 'サービスID');
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->orderBy('created_at', 'desc');

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
        $show = new Show(AddOrderDetail::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('addOrder.id', '追加注文ID');
        $show->field('service.title', 'サービス');
        $show->field('serviceOption.title', 'オプション');
        $show->field('quantity', '数量');
        $show->field('price', '単価')->display(function ($price) {
            return '¥' . number_format($price);
        });
        $show->field('total_price', '合計')->display(function ($price) {
            return '¥' . number_format($price);
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
        $form = new Form(new AddOrderDetail());

        $form->select('add_order_id', '追加注文')->options(function ($id) {
            $addOrder = \App\Models\AddOrder::find($id);
            if ($addOrder) {
                return [$addOrder->id => '追加注文 #' . $addOrder->id];
            }
        })->ajax('/admin/api/add_orders');
        $form->select('service_id', 'サービス')->options(function ($id) {
            $service = \App\Models\Service::find($id);
            if ($service) {
                return [$service->id => $service->title];
            }
        })->ajax('/admin/api/services');
        $form->select('service_option_id', 'サービスオプション')->options(function ($id) {
            $serviceOption = \App\Models\ServiceOption::find($id);
            if ($serviceOption) {
                return [$serviceOption->id => $serviceOption->title];
            }
        })->ajax('/admin/api/service_options');
        $form->decimal('price', '単価');
        $form->number('quantity', '数量')->default(1);
        $form->decimal('total_price', '合計');

        return $form;
    }
}

