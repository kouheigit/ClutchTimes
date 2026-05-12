<?php

namespace App\Admin\Controllers;

use App\Models\CartDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CartDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'カート明細管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CartDetail());

        $grid->column('id', 'ID')->sortable();
        $grid->column('cart.id', 'カートID');
        $grid->column('cart.user.name', 'ユーザー');
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
            $filter->equal('cart_id', 'カートID');
            $filter->equal('service_id', 'サービスID');
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->with(['cart.user', 'service', 'serviceOption'])->orderBy('created_at', 'desc');

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
        $show = new Show(CartDetail::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('cart.id', 'カートID');
        $show->field('cart.user.name', 'ユーザー');
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
        $form = new Form(new CartDetail());

        $form->select('cart_id', 'カート')->options(function ($id) {
            $cart = \App\Models\Cart::find($id);
            if ($cart) {
                return [$cart->id => 'カート #' . $cart->id . ' (' . $cart->user->name . ')'];
            }
        })->ajax('/admin/api/carts');
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

