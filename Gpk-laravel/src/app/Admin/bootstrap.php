<?php

use Encore\Admin\Facades\Admin;

/**
 * Laravel-admin bootstrap file.
 *
 * This file is bootstrapped for every request.
 */

// メニュー設定
Admin::menu(function (\Encore\Admin\Widgets\Navbar $menu) {
    // ダッシュボード
    $menu->add([
        'text' => 'ダッシュボード',
        'icon' => 'fa-bar-chart',
        'url' => admin_url('/'),
    ]);
    
    // ユーザー管理
    $menu->add([
        'text' => 'ユーザー管理',
        'icon' => 'fa-users',
        'children' => [
            [
                'text' => 'ユーザー一覧',
        'url' => admin_url('users'),
            ],
            [
                'text' => 'ポイント管理',
                'url' => admin_url('user_points'),
            ],
            [
                'text' => 'ポイントログ',
                'url' => admin_url('user_point_logs'),
            ],
        ],
    ]);
    
    // 予約管理
    $menu->add([
        'text' => '予約管理',
        'icon' => 'fa-calendar-check-o',
        'url' => admin_url('reservations'),
    ]);
    
    // ホテル・サービス管理
    $menu->add([
        'text' => 'ホテル・サービス',
        'icon' => 'fa-building',
        'children' => [
            [
                'text' => 'ホテル管理',
                'url' => admin_url('hotels'),
            ],
            [
                'text' => 'サービス管理',
                'url' => admin_url('services'),
            ],
            [
                'text' => 'サービスオプション',
                'url' => admin_url('service_options'),
            ],
        ],
    ]);
    
    // カレンダー管理
    $menu->add([
        'text' => 'カレンダー管理',
        'icon' => 'fa-calendar',
        'children' => [
            [
                'text' => 'FIXDAY（固定日）',
                'url' => admin_url('calendars'),
            ],
            [
                'text' => 'FREEDAY（自由日）',
                'url' => admin_url('freedays'),
            ],
            [
                'text' => 'カレンダーオプション',
                'url' => admin_url('calendar_options'),
            ],
        ],
    ]);
    
    // 注文管理
    $menu->add([
        'text' => '注文管理',
        'icon' => 'fa-shopping-cart',
        'children' => [
            [
                'text' => '注文一覧',
                'url' => admin_url('orders'),
            ],
            [
                'text' => '注文明細',
                'url' => admin_url('order_details'),
            ],
            [
                'text' => '追加注文',
                'url' => admin_url('add_orders'),
            ],
            [
                'text' => '追加注文明細',
                'url' => admin_url('add_order_details'),
            ],
            [
                'text' => 'カート管理',
                'url' => admin_url('carts'),
            ],
            [
                'text' => 'カート明細',
                'url' => admin_url('cart_details'),
            ],
        ],
    ]);
    
    // 招待管理
    $menu->add([
        'text' => '招待管理',
        'icon' => 'fa-envelope',
        'url' => admin_url('invitations'),
    ]);
    
    // お知らせ・情報管理
    $menu->add([
        'text' => 'お知らせ・情報',
        'icon' => 'fa-bullhorn',
        'children' => [
            [
                'text' => 'お知らせ',
                'url' => admin_url('news'),
            ],
            [
                'text' => '情報',
                'url' => admin_url('information'),
            ],
        ],
    ]);
    
    // システム管理
    $menu->add([
        'text' => 'システム管理',
        'icon' => 'fa-cog',
        'children' => [
            [
                'text' => '休日管理',
                'url' => admin_url('holidays'),
            ],
            [
                'text' => '決済ログ',
                'url' => admin_url('veritrans_logs'),
            ],
            [
                'text' => '予約ログ',
                'url' => admin_url('reservation_logs'),
            ],
            [
                'text' => 'リリースログ',
                'url' => admin_url('release_logs'),
            ],
            [
                'text' => 'メールテンプレート',
                'url' => admin_url('mail_templates'),
            ],
        ],
    ]);
});

// カスタムCSS/JSの読み込みなど、管理画面の初期設定を行う
Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    // カスタム設定があればここに記述
});
