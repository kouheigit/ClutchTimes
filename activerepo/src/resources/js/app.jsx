/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import './components/Example';
import React from 'react'
import ReactDOM from 'react-dom/client'
import Counter from './components/Counter'
import Day1 from './components/Day1'
import Todo from './components/Todo'
import Todo1 from './components/Todo1'
import Todo2 from './components/Todo2'
import Todo3 from './components/Todo3'
import Todo4 from './components/Todo4'
import Practice1 from './components/practice/Practice1'


//$test = new test();クラスのインスタンス化
const roofElement = document.getElementById('react-root');
if(roofElement){
    //$test1 = $test->method(10);メソット呼び出し
    const root = ReactDOM.createRoot(roofElement)
    //return view('components.counter');
    root.render(<Counter />)
}

//react-day1root
const day1Element = document.getElementById('react-day1root');
if(day1Element){
    const day1 = ReactDOM.createRoot(day1Element)
    day1.render(<Day1 />)
}
//react-roottodo
const todoElement = document.getElementById('react-roottodo')
if(todoElement){
    const todo = ReactDOM.createRoot(todoElement)
    todo.render(
        <>
            <Todo />
            <Todo1 />
            <Todo2 />
            <Todo3 />
            <Todo4 />
        </>
    );
}

const practice1Element = document.getElementById('Practice1');
if(practice1Element){
    const practice1 = ReactDOM.createRoot(practice1Element)
    practice1.render(<Practice1 />)
}




