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
        //値を取得
        $title = $request->input('title');
        $date1 = $request->input('date');
        $time = $request->input('time');
        $comment = $request->input('comment');

        //$todayはテスト項目
        $today = date("Y-m-d H:i:s");
        //取得した値をY-m-d型に変換
        $date2 = $date1 .$time;
        $date = date('Y-m-d H:i:s' ,strtotime($date2));

        //テストコード$date(予定の日時)が$today(現在の日時)に至ってない。
        //ニュース画面にこの処理を入れる
        if($date > $today){
            $date ="まだ投稿予定の時刻じゃありません";
        }

        return view('admin.addnews',compact('title','date','comment','today'));
    }
}
