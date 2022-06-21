<?php

namespace App\Http\Controllers;
use App\Models\Admin;
use App\Http\Requests\VoteRequest;
use App\Http\Requests\HomeRequest;
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
    public function article(HomeRequest $request){
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
        $questions = DB::table('questions')->where('start_date', '<=', $today)->where('end_date', '>', $today)->orderBy('start_date', 'desc')->get();


        foreach($questions as $question){
            //idを全て取得->で取得
            $question_id = $question->id;
   //     }


        //---------以下はテスト処理後で消す

//       $id =  DB::table('questions')->where('start_date', '<=', $today)->where('end_date', '>', $today)->orderBy('start_date', 'desc')->orderBy('id')->pluck('id');


     //  foreach($id as $question_id){
           //質問においての総回答数を取得する
           $all_answer = DB::table('user_answers')->where('question_id','=',$question_id)->pluck('answer')->count();

           //各回答数を取得する
           //質問の中の全体数の中のanswer1の割合
           $answer1 = DB::table('user_answers')->where('question_id','=',$question_id)->where('answer','=','answer1')->pluck('answer')->count();
           //質問の中の全体数の中のanswer2の割合
           $answer2 = DB::table('user_answers')->where('question_id','=',$question_id)->where('answer','=','answer2')->pluck('answer')->count();
           //質問の中の全体数の中のanswer3の割合
           $answer3 = DB::table('user_answers')->where('question_id','=',$question_id)->where('answer','=','answer3')->pluck('answer')->count();

           //変数名show_は投票率を計算している、answer_は名前を取得している
           //answer_はクイズのタイトル名を取得する
           //
           if($answer1==0){
               $show_answer1 = 0;
           }else{
               $show_answer1 = round($answer1 / $all_answer * 100);
           }

           $answer1_title = DB::table('questions')->where('id','=',$question_id)->value('answer1');

           if($answer2==0){
               $show_answer2 = 0;
           }else{
               $show_answer2 = round($answer2 / $all_answer * 100);
           }
           $answer2_title = DB::table('questions')->where('id','=',$question_id)->value('answer2');



           $answer3_title = DB::table('questions')->where('id','=',$question_id)->value('answer3');
           $show_answer4 = "next";

           if(empty($answer3_title)){
               $answer3_title = null;
               $show_answer3 = null;
           }elseif($answer3==0){
               $show_answer3 = 0;
           }else{
               $show_answer3 = round($answer3 / $all_answer * 100);
           }

           $show_answer = array($show_answer1,$answer1_title,$show_answer2,$answer2_title,$show_answer3,$answer3_title,$show_answer4);
           //変数に値を格納する
           foreach($show_answer as $show_answers){
               $show_value[$question_id] = $show_answers;
           }

           /*
           $test6[] = $test;
           $test6[] = $test1;
           $test6[] = $test2;
           $test6[] = $test3;*/
           //$tests[] = $test1;
       }
       //--------テスト処理終了---------

       // $questions = DB::table('questions')->get();
        return view('auth.betshow',compact('questions','show_value'));
    }

    /*
    public function betshowpost(Request $request){
        session_start();
        $id = $request->input('id');
        $_SESSION['id'] = $id;
        return redirect('home/vote');
    }*/
    //VoteRequest←一時保留
    public function vote(VoteRequest $request){
        $user_id = Auth::user()->id;
        $id = $request->input('id');
        $questions = DB::table('questions')->where('id', $id)->get();


        $all_answer = DB::table('user_answers')->where('question_id','=',$id)->pluck('answer')->count();
        $answer1 = DB::table('user_answers')->where('question_id','=',$id)->where('answer','=','answer1')->pluck('answer')->count();

        if($answer1==0){
            $show_answer1 = 0;
        }else{
            $show_answer1= round($answer1 / $all_answer * 100);
        }

        $answer1_title = DB::table('questions')->where('id','=',$id)->value('answer1');


        $answer2 = DB::table('user_answers')->where('question_id','=',$id)->where('answer','=','answer2')->pluck('answer')->count();

        if($answer2==0){
           $show_answer2 =0;
       }else{
           $show_answer2= round($answer2 / $all_answer * 100);
       }

        $answer2_title = DB::table('questions')->where('id','=',$id)->value('answer2');


        $answer3 = DB::table('user_answers')->where('question_id','=',$id)->where('answer','=','answer3')->pluck('answer')->count();

        //$show_answer3 = round($answer3 / $all_answer * 100);

        $answer3_title = DB::table('questions')->where('id','=',$id)->value('answer3');

        if(empty($answer3_title)){
            $answer3_title = null;
            $show_answer3 = null;
        }elseif($answer3==0){
            $show_answer3 = 0;
        }else {
            $show_answer3 = round($answer3 / $all_answer * 100);
        }
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
        return view('auth.vote',compact('questions','user_id','show_answer1','show_answer2','show_answer3','answer1_title','answer2_title','answer3_title'));
    }
    public function votepost(Request $request){
        //ユーザーのIDを取得

        $user_id = $request->input('user_id');
        //問題のidを取得
        $question_id = $request->input('question_id');
        $answer = $request->input('answer');


        //クエリビルダで値を検索する同じユーザーが投票できないようにする処理を記述
        //---再度投票禁止処理、不要指示が出たら以下は削除する--

        $test = DB::table('user_answers')->where('user_id','=',$user_id)->Where('question_id','=',$question_id)->value('answer');
        if($test==null) {
            //投票内容を挿入する
            $value = [
                'user_id' => $user_id,
                'question_id' => $question_id,
                'answer' => $answer,
                'bet_points' => null,
            ];
            DB::table('user_answers')->insert($value);
            $error_message = null;
        }else{
            $error_message = "以前に投票されました";
        }
        //------再度投票禁止処理終了-------

       // DB::table('user_answers')->insert($value);

        return view('auth.votepost',compact('test','error_message'));
    }
}
