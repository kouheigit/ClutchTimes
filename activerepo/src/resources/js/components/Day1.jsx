import React, { useState } from 'react';
export default function Day1(){
    const [count,setCount] = useState(0);
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

