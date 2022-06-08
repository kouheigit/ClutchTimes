<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = date("Y-m-d H:i:s");//今日の日時を取得する


       // $tests = DB::table('admin_news_table')->select('date')->get();
        //データ情報一覧を取得する
        //$test = DB::table('admin_news_table')->pluck("date");
         $tests = DB::table('admin_news_table')->orderBy('date', 'asc')->pluck("date");

        $test3 = array();
        foreach($tests as $test1){
            //日時以降のものを取得する
            //現在の日時以降のものは取得できた
            if($today > $test1){
                //echo $test1;
                $test2 = DB::table('admin_news_table')->where('date','=',$test1)->value("title");
                echo $test2;
                 $test3 = $test2;
                //echo "<br>";
            }

        }
        //$test3 = ["Syamu","jtshibatar","HIKAKIN"];
        /*
        if($date > $today){
            $date ="まだ投稿予定の時刻じゃありません";
        }*/
        $msg ="あららトゥーらー";
        return view('home',compact('msg','tests','test2','test3'));
    }
}
