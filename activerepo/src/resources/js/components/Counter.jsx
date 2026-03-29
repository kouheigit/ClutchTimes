//import React, { useState } from 'react';はrequire_once 'React.php'; // ライブラリ本体の読み込み
//import React, { useState } from 'react';は→ use App\Helpers\StateManager; // 状態操作の機能を使えるようにする

import React, { useState } from 'react';
export default function Counter(){
    // list(count, setCount) に相当。セッションで言えば count = $_SESSION['count'];
    const [ count,setCount] = useState(0)
    return (
        <div>
            <h2>カウンターアプリ</h2>
            <p>現在のカウント{count}</p>
            <button onClick={() => setCount(count + 1)}>+1</button>
            <button onClick={() => setCount(count - 1)}>-1</button>
            <button onClick={() => setCount(0)}>リセット</button>
        </div>
    )
}
/*
<?php
session_start();

// 初期値の設定（useState(0) に相当）
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}

// ボタンが押されたら（POST）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['count']++; // ← setCount(count + 1) に相当
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!-- 表示部分 -->
<p>現在のカウント: <?php echo $_SESSION['count']; ?></p>

<form method="POST">
    <button type="submit">+1</button>
</form>


 */
