<?php

namespace App\Http\Controllers\Admin;  // Adminを追加
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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

        $validate_rule =[
            'title' =>'between:0,100',
            'comment'=>'between:0,1000',
        ];

        $this->validate($request,$validate_rule);

        //値を取得
        $title = $request->input('title');
        //日付、時刻を取得
        $date1 = $request->input('date');
        $time = $request->input('time');
        //本文を取得
        $comment = $request->input('comment');


        $date2 = $date1 .$time;
        //取得した値をY-m-d型に変換
        $date = date('Y-m-d H:i:s' ,strtotime($date2));




        //テストコード$date(予定の日時)が$today(現在の日時)に至ってない。
        //ニュース画面にこの処理を入れる

        /* (予約投稿メソット)
        $today = date("Y-m-d H:i:s");//今日の日付を取得する
        if($date > $today){
            $date ="まだ投稿予定の時刻じゃありません";
        }*/

        /*(insertメソット全てが完成したらコメントアウトを外す)
        $value = [
            'title'=>$title,
            'date'=>$date,
            'text'=>$comment,
        ];
        DB::table('admin_news_table')->insert($value);
*/
        return view('admin.addnews',compact('title','date','comment'));
    }
}
