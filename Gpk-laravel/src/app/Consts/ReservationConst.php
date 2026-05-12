<?php

namespace App\Consts;

class ReservationConst
{
    // ステータス定数
    const STATUS_APPLYING = 1;           // 申込中
    const STATUS_UNDER_RESERVATION = 2;  // 予約中
    const STATUS_RESERVED = 3;           // 予約確定
    const STATUS_CHECKED_IN = 4;         // チェックイン済
    const STATUS_CHECKED_OUT = 5;        // チェックアウト済
    const STATUS_CANCELING = 8;          // キャンセル中
    const STATUS_CANCEL = 9;             // キャンセル

    // ステータスリスト
    const STATUS_LIST = [
        self::STATUS_APPLYING => '申込中',
        self::STATUS_UNDER_RESERVATION => '予約中',
        self::STATUS_RESERVED => '予約確定',
        self::STATUS_CHECKED_IN => 'チェックイン済',
        self::STATUS_CHECKED_OUT => 'チェックアウト済',
        self::STATUS_CANCELING => 'キャンセル中',
        self::STATUS_CANCEL => 'キャンセル',
    ];

    // 決済方法
    const PAYMENT_CASH = 0;      // 現地払い
    const PAYMENT_CREDIT = 1;    // クレジット
}

