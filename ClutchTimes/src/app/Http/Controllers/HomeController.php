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
        //今日の日時を取得する
        $today = date("Y-m-d H:i:s");//今日の日時を取得する
/*
 * 変数変更　test4は showvalueに変更
 */


        //データ情報一覧を取得する
        //$test = DB::table('admin_news_table')->pluck("date");
        //全ての日付及びデータを取得
         $alldate = DB::table('admin_news_table')->orderBy('date', 'asc')->pluck("date");
        $datetimeArray = Array();
        foreach($alldate as $alldate1){
            //日時以降のものを取得する
            //現在の日時以降のものは取得できた
            if($today > $alldate1){
                //titleを取得
                $title = DB::table('admin_news_table')->where('date','=',$alldate1)->value("title");
                //日時を取得
                $date = DB::table('admin_news_table')->where('date','=',$alldate1)->value("date");
                //タイトル、日時を結合
                $datetime =$title.$date;
                //配列化
                $datetimeArray[] = $datetime;
                //$test3[] = $test2;
                //echo gettype( $test2 );
            }
        }
        //値を最新順に入れ替える
        $datetimeArray_reverse = array_reverse($datetimeArray);
        //var_dump($datetimeArray);

        //配列の中身が存在するかどうか確認
        if (array_key_exists(0, $datetimeArray_reverse)) {
            $showvalue = $datetimeArray_reverse[0];
            // キー名が存在する場合
        }
        if (array_key_exists(1, $datetimeArray_reverse)) {
            $showvalue1 = $datetimeArray_reverse[1];
        }
        if (array_key_exists(2, $datetimeArray_reverse)) {
            $showvalue2 = $datetimeArray_reverse[2];
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
        return view('home',compact('showvalue','showvalue1','showvalue2'));
    }
    //postに後で変更
    public function article(Request $request){
        $articlevalue = $request->input('articlevalue');
        //日時のみを切り出す
        $datetime_strpos = substr($articlevalue, -19);
        //日時を整形する
        $datetime = date('Y-m-d H:i:s' ,strtotime($datetime_strpos));
        //タイトルを取得
        $title = DB::table('admin_news_table')->where('date', 'Like', $datetime)->value("title");
        //$Ymd = date('Y-m-d H:i:s' ,strtotime($articlevalue));

        return view('auth.article',compact('articlevalue','datetime','title'));
    }
}
