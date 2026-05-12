<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Consts\UserConst;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ユーザー管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        
        $grid->column('id', 'ID')->sortable();
        $grid->column('member_id', '会員ID');
        $grid->column('name', '氏名');
        $grid->column('email', 'メール');
        $grid->column('tel', '電話番号');
        $grid->column('type', 'タイプ')->using(UserConst::TYPE_LIST)->label([
            UserConst::TYPE_GENERAL => 'info',
            UserConst::TYPE_OWNER => 'success',
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
            $filter->like('member_id', '会員ID');
            $filter->like('name', '氏名');
            $filter->like('email', 'メール');
            $filter->equal('type', 'タイプ')->select(UserConst::TYPE_LIST);
            $filter->equal('status', 'ステータス')->select([0 => '無効', 1 => '有効']);
            $filter->between('created_at', '登録日')->datetime();
        });
        
        // アクション
        $grid->actions(function ($actions) {
            // 削除ボタン非表示（ソフトデリートのみ）
            $actions->disableDelete();
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
        $show = new Show(User::findOrFail($id));
        
        $show->field('id', 'ID');
        $show->field('member_id', '会員ID');
        $show->field('name', '氏名');
        $show->field('email', 'メール');
        $show->field('last_name', '姓');
        $show->field('first_name', '名');
        $show->field('last_kana', '姓（カナ）');
        $show->field('first_kana', '名（カナ）');
        $show->field('zip1', '郵便番号1');
        $show->field('zip2', '郵便番号2');
        $show->field('address1', '住所1');
        $show->field('address2', '住所2');
        $show->field('tel', '電話番号');
        $show->field('type', 'タイプ')->using(UserConst::TYPE_LIST);
        $show->field('status', 'ステータス')->using([0 => '無効', 1 => '有効']);
        $show->field('created_at', '登録日時');
        $show->field('updated_at', '更新日時');
        
        // リレーション
        $show->hotels('所属ホテル', function ($hotels) {
            $hotels->setResource('/admin/hotels');
            $hotels->id();
            $hotels->name();
        });
        
        $show->reservations('予約履歴', function ($reservations) {
            $reservations->setResource('/admin/reservations');
            $reservations->id();
            $reservations->checkin_date();
            $reservations->checkout_date();
            $reservations->status();
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
        $form = new Form(new User());
        
        $form->text('member_id', '会員ID');
        $form->text('name', '氏名')->required();
        $form->email('email', 'メール')->required();
        $form->password('password', 'パスワード');
        
        $form->divider('個人情報');
        $form->text('last_name', '姓');
        $form->text('first_name', '名');
        $form->text('last_kana', '姓（カナ）');
        $form->text('first_kana', '名（カナ）');
        $form->text('zip1', '郵便番号1')->placeholder('123');
        $form->text('zip2', '郵便番号2')->placeholder('4567');
        $form->text('address1', '住所1（都道府県・市区町村）');
        $form->text('address2', '住所2（番地・建物名）');
        $form->text('tel', '電話番号');
        
        $form->divider('システム設定');
        $form->select('type', 'ユーザータイプ')->options(UserConst::TYPE_LIST)->default(1);
        $form->switch('status', 'ステータス')->default(1);
        $form->multipleSelect('hotels', 'ホテル')->options(\App\Models\Hotel::all()->pluck('name', 'id'));
        
        // 保存前処理
        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });
        
        return $form;
    }
}
