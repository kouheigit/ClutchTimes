<?php

namespace App\Http\Controllers\Admin;  // Adminを追加

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');  //変更
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');  //変更
    }
    //追加　ニュース追加欄
    public function addnews(Request $request)
    {
        return view('admin.addnews');
    }
    public function  addnewspost(Request $request)
    {
        $msg = $request->input('msg');
        return view('admin.addnews',compact('msg'));
    }
}
