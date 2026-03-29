import { useCounter } from "./hooks/useCounter1";
import React, { useState,useEffect  } from "react";

    export default function Todo3() {
        //配列を格納する
        const [todos, setTodos] = useState([]);
        const [inputs, setInput] = useState('');
        const [tests, setTest] = useState([]);

        const [dtests,deleteTest] = useState(['いちろう','じろう','さぶろう','しろう']);


        const addTodo = () => {
            if (inputs.trim() == '') return;
            setTodos([...todos, {text: inputs, done: false}]);
            setInput('');
        }

        //filter関数について
        //filter関数は指定された値をピックアップする関数
        const numbers = [1, 2, 3, 4, 5];
        const even = numbers.filter(n => n % 2 === 0);
        console.log(even);

        //addfilterの実装
        const addfilter = () => {
            const words = ["plumber", "attire", "bank", "character", "choose"];
            const result = words.filter(word => word.includes('c'));
            setTest(result);
        }

        const deletefilter = () => {
            deleteTest(
                dtests.filter((dtest,index)=>(dtest !== 'いちろう'))
            )
        }



        return (
            <div className="todo3">
                <input type="text" value={inputs} onChange={(e) => setInput(e.target.value)}/>
                <button onClick={addTodo}>追加</button>
                <button onClick={addfilter}>cがつく特定ワード抽出ボタン</button>
                <button onClick={deletefilter}>いちろうの文字列を削除する</button>
                <p>入力された値{inputs}</p>
                {todos.map((todo, index) => (
                    <li key={index}>
                        <p>{todo.text}</p>
                    </li>
                ))}
                {tests.map((test, index) => (
                    <li key={index}>
                        <p>{test}</p>
                    </li>
                ))}
                {dtests.map((dtest,index) =>(
                    <li key={index}>
                        <p>{dtest}</p>
                    </li>
                ))}
            </div>
        );
}
