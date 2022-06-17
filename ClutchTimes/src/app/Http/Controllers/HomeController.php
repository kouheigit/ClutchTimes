<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Http\Requests\VoteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use mysql_xdevapi\Table;

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
        $today = date("Y-m-d H:i:s");
        $news = DB::table('admin_news_table')->where('date', '<=', $today)->orderBy('date', 'desc')->limit(3)->get();
        return view('home',compact('news'));

        /*エラーが出るところを見つけ次第 Slackで送られてきたものに修正する個々に記述せよ
        以下は一時的に除外する($todayは持ってくる)*/
        /*
        $showvalue = null;
        $showvalue1 = null;
        $showvalue2 = null;
        //今日の日時を取得する
        $today = date("Y-m-d H:i:s");//今日の日時を取得する



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
        return view('home',compact('showvalue','showvalue1','showvalue2'));*/
    }
    //postに後で変更
    public function article(Request $request){
       // $articlevalue = $request->input('id');
        //$id = $request->input('id');

        $id = $request->input('articlevalue');
        $news = DB::table('admin_news_table')->where('id', $id)->get();
        return view('auth.article',compact('news'));
        //値を取得する
        /*元コード【除外】
        $articlevalue = $request->input('articlevalue');

        //日時のみを切り出す
        $datetime_strpos = substr($articlevalue, -19);
        //タイトルのみ切り出す
        $title_strpos = str_replace($datetime_strpos,'',$articlevalue);
        //日時を整形する
        $datetime = date('Y-m-d H:i:s' ,strtotime($datetime_strpos));

        //投稿時刻とタイトルの二重で検索する
        $title = DB::table('admin_news_table')->where('date', 'Like', $datetime)->where('title', 'Like', $title_strpos)->value("title");
        $text = DB::table('admin_news_table')->where('date', 'Like', $datetime)->where('title', 'Like', $title_strpos)->value("text");
        //$Ymd = date('Y-m-d H:i:s' ,strtotime($articlevalue));

        return view('auth.article',compact('title','text'));*/

    }
    //bet画面一覧を取得
    public function betshow(Request $request){
        $today = date("Y-m-d H:i:s");
        //limit不要
        $questions = DB::table('questions')->where('start_date', '<=', $today)->where('end_date', '>', $today)->orderBy('start_date', 'desc')->get();
       // $questions = DB::table('questions')->get();
        return view('auth.betshow',compact('questions'));
    }

    /*
    public function betshowpost(Request $request){
        session_start();
        $id = $request->input('id');
        $_SESSION['id'] = $id;
        return redirect('home/vote');
    }*/
    //VoteRequest←一時保留
    public function vote(Request $request){
        $user_id = Auth::user()->id;
        $id = $request->input('id');
        $questions = DB::table('questions')->where('id', $id)->get();
        /*
        session_start();
        //バリデをかける
        $id = $_SESSION['id'];
        if (empty($id)) {
            return redirect('home/betshow');
        }elseif(is_null($id)){
            return redirect('home/betshow');
        }
        $_SESSION['id'] = null;*/
        return view('auth.vote',compact('questions','user_id'));
    }
    public function votepost(Request $request){
        //ユーザーのIDを取得
        $error_message = null;
        $user_id = $request->input('user_id');
        //問題のidを取得
        $question_id = $request->input('question_id');
        $answer = $request->input('answer');
        //クエリビルダで値を検索する同じユーザーが投票できないようにする処理を記述


        //投票内容を挿入する
        $value = [
            'user_id' => $user_id,
            'question_id' => $question_id,
            'answer' => $answer,
            'bet_points' => null,
        ];
        DB::table('user_answers')->insert($value);

        return view('auth.votepost');
    }
}
