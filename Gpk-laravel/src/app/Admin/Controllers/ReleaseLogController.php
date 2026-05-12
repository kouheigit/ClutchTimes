<?php

namespace App\Admin\Controllers;

use App\Models\ReleaseLog;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReleaseLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'リリースログ管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ReleaseLog());

        $grid->column('id', 'ID')->sortable();
        $grid->column('calendar.id', 'カレンダーID');
        $grid->column('user.name', 'ユーザー');
        $grid->column('action', 'アクション');
        $grid->column('data', 'データ')->display(function ($data) {
            if ($data) {
                return '<pre>' . json_encode(json_decode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            }
            return '-';
        });
        $grid->column('created_at', '作成日時')->display(function ($created_at) {
            return date('Y-m-d H:i', strtotime($created_at));
        });
        
        // フィルター
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->equal('calendar_id', 'カレンダーID');
            $filter->like('user.name', 'ユーザー名');
            $filter->like('action', 'アクション');
            $filter->between('created_at', '作成日時')->datetime();
        });
        
        $grid->model()->with(['calendar', 'user'])->orderBy('created_at', 'desc');

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
        $show = new Show(ReleaseLog::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('calendar.id', 'カレンダーID');
        $show->field('user.name', 'ユーザー');
        $show->field('action', 'アクション');
        $show->field('data', 'データ')->unescape()->as(function ($data) {
            if ($data) {
                return '<pre>' . json_encode(json_decode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            }
            return '-';
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
        $form = new Form(new ReleaseLog());

        $form->select('calendar_id', 'カレンダー')->options(function ($id) {
            $calendar = \App\Models\Calendar::find($id);
            if ($calendar) {
                return [$calendar->id => 'カレンダー #' . $calendar->id];
            }
        })->ajax('/admin/api/calendars');
        $form->select('user_id', 'ユーザー')->options(function ($id) {
            $user = \App\Models\User::find($id);
            if ($user) {
                return [$user->id => $user->name];
            }
        })->ajax('/admin/api/users');
        $form->text('action', 'アクション');
        $form->textarea('data', 'データ');

        return $form;
    }
}

