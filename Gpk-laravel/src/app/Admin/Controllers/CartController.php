<?php

namespace App\Admin\Controllers;

use App\Models\Cart;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CartController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'カート管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Cart());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'ユーザー');
        $grid->column('user.email', 'メールアドレス');
        $grid->column('cartDetails', '明細数')->display(function ($cartDetails) {
            return count($cartDetails) . '件';
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        $grid->column('updated_at', '更新日時')->display(function ($updated_at) {
            return date('Y-m-d H:i', strtotime($updated_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('user.name', 'ユーザー名');
            $filter->like('user.email', 'メールアドレス');
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->with(['user', 'cartDetails'])->orderBy('created_at', 'desc');

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
        $show = new Show(Cart::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user.name', 'ユーザー');
        $show->field('user.email', 'メールアドレス');
        $show->field('created_at', '作成日時');
        $show->field('updated_at', '更新日時');
        
        // カート明細
        $show->cartDetails('カート明細', function ($cartDetails) {
            $cartDetails->setResource('/admin/cart_details');
            $cartDetails->id();
            $cartDetails->column('service.title', 'サービス');
            $cartDetails->column('serviceOption.title', 'オプション');
            $cartDetails->quantity('数量');
            $cartDetails->column('price', '単価')->display(function ($price) {
                return '¥' . number_format($price);
            });
            $cartDetails->column('total_price', '合計')->display(function ($price) {
                return '¥' . number_format($price);
            });
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
        $form = new Form(new Cart());

        $form->select('user_id', 'ユーザー')->options(function ($id) {
            $user = \App\Models\User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users');

        return $form;
    }
}

