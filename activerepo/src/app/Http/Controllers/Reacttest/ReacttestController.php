<?php

namespace App\Http\Controllers\Reacttest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReacttestController extends Controller
{
    public function index(Request $request)
    {
        return view('reacttest.index');
    }

    public function day1(Request $request)
    {
        return view('reacttest.Day1');
    }

    public function todo(Request $request)
    {
        return view('reacttest.todo');
    }
    public function reactpractice(Request $request)
    {
        return view('reacttest.reactpractice');
    }
}
