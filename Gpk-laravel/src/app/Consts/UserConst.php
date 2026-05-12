<?php

namespace App\Consts;

class UserConst
{
    // ユーザータイプ
    const TYPE_GENERAL = 1;  // 一般
    const TYPE_OWNER = 2;    // オーナー

    // タイプリスト
    const TYPE_LIST = [
        self::TYPE_GENERAL => '一般',
        self::TYPE_OWNER => 'オーナー',
    ];

    // ステータス
    const STATUS_INACTIVE = 0;  // 無効
    const STATUS_ACTIVE = 1;    // 有効
}

