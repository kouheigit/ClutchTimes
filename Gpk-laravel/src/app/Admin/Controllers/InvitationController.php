<?php

namespace App\Admin\Controllers;

use App\Models\Invitation;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class InvitationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '招待管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Invitation());

        $grid->column('id', 'ID')->sortable();
        $grid->column('reservation.id', '予約ID');
        $grid->column('owner.name', 'オーナー');
        $grid->column('name', '招待者名');
        $grid->column('email', 'メールアドレス');
        $grid->column('status', 'ステータス')->using([
            1 => '招待中',
            2 => '登録済み',
        ])->label([
            1 => 'warning',
            2 => 'success',
        ]);
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        $grid->model()->orderBy('created_at', 'desc');
        
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('name', '招待者名');
            $filter->like('email', 'メールアドレス');
            $filter->equal('status', 'ステータス')->select([
                1 => '招待中',
                2 => '登録済み',
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
        $show = new Show(Invitation::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('reservation.id', '予約ID');
        $show->field('owner.name', 'オーナー');
        $show->field('user.name', 'ゲストユーザー');
        $show->field('name', '招待者名');
        $show->field('email', 'メールアドレス');
        $show->field('token', 'トークン');
        $show->field('status', 'ステータス')->using([
            1 => '招待中',
            2 => '登録済み',
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
        $form = new Form(new Invitation());

        $form->select('reservation_id', '予約')
            ->options(\App\Models\Reservation::all()->pluck('id', 'id'))
            ->required();
        $form->select('owner_id', 'オーナー')
            ->options(\App\Models\User::where('type', 2)->pluck('name', 'id'))
            ->required();
        $form->text('name', '招待者名')->required();
        $form->email('email', 'メールアドレス')->required();
        $form->select('status', 'ステータス')->options([
            1 => '招待中',
            2 => '登録済み',
        ])->default(1);

        return $form;
    }
}
