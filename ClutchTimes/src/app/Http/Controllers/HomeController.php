<?php

namespace App\Http\Controllers;
use App\Models\Admin;
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
        $showvalue = null;
        $showvalue1 = null;
        $showvalue2 = null;

        $today = date("Y-m-d H:i:s");//今日の日時を取得する
/*
 * 変数変更　test4は showvalueに変更
 */


        //データ情報一覧を取得する
        //$test = DB::table('admin_news_table')->pluck("date");
        //全てのデータを取得
         $alldate = DB::table('admin_news_table')->orderBy('date', 'asc')->pluck("date");

        $test3 = Array();
        foreach($alldate as $alldate1){
            //日時以降のものを取得する
            //現在の日時以降のものは取得できた
            if($today > $alldate1){
                //titleを取得
                $title = DB::table('admin_news_table')->where('date','=',$alldate1)->value("title");
                //日時を取得
                $date = DB::table('admin_news_table')->where('date','=',$alldate1)->value("date");
                echo $title;

                $datetime =$title.$date;
                $test3[] = $datetime;
                //$test3[] = $test2;
                //echo gettype( $test2 );
                echo "<br>";
            }
        }
        $testnon = array_reverse($test3);
        var_dump($test3);
        //配列の中身が存在するかどうか
        if (array_key_exists(0, $testnon)) {
            $showvalue = $testnon[0];
            // キー名が存在する場合
        }
        if (array_key_exists(1, $testnon)) {
            $showvalue1 = $testnon[1];
        }
        if (array_key_exists(2, $testnon)) {
            $showvalue2 = $testnon[2];
        }
/*
         $showvalue = $testnon[0];
         $showvalue1 = $testnon[1];
         $showvalue2 = $testnon[2];*/

        //$test3 = ["Syamu","jtshibatar","HIKAKIN"];
        /*
        if($date > $today){
            $date ="まだ投稿予定の時刻じゃありません";
        }*/
        $msg ="あららトゥーらー";
        return view('home',compact('msg','title','showvalue','showvalue1','showvalue2'));
    }
}
