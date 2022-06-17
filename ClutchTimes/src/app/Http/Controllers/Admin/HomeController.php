<?php

namespace App\Http\Controllers\Admin;  // Adminを追加
use App\Http\Requests\AddnewsRequest;
use App\Http\Requests\BetregisterRequest;
//use Illuminate\Support\Facades\Validator;
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
    public function  addnewspost(AddnewsRequest $request)
    {

        //値を取得
        $title = $request->input('title');
        //日付、時刻を取得
        $date1 = $request->input('date');
        $time = $request->input('time');
        //本文を取得
        $comment = $request->input('comment');

        $date2 = $date1 .$time;
        //取得した値をY-m-d型に変換【重要】
        $date = date('Y-m-d H:i:s' ,strtotime($date2));


        //テストコード$date(予定の日時)が$today(現在の日時)に至ってない。
        //ニュース画面にこの処理を入れる

        /* (予約投稿メソット)
        $today = date("Y-m-d H:i:s");//今日の日付を取得する
        if($date > $today){
            $date ="まだ投稿予定の時刻じゃありません";
        }*/

        $value = [
            'title'=>$title,
            'date'=>$date,
            'text'=>$comment,
        ];
        DB::table('admin_news_table')->insert($value);

        $complete ="投稿が完了しました";
        return view('admin.addnews',compact('complete'));
    }

    //記事削除
    public function deletearticle(Request $request)
    {

        $today = date("Y-m-d H:i:s");
        // $news = DB::table('admin_news_table')->where('date', '<=', $today)->orderBy('date', 'desc')->limit(3)->get();
        $hidden_news = DB::table('admin_news_table')->where('date', '>', $today)->orderBy('date', 'desc')->get();
        $news = DB::table('admin_news_table')->where('date', '<=', $today)->orderBy('date', 'desc')->get();
        return view('admin.deletearticle', compact('news', 'hidden_news'));
    }
    public function deletemessage(Request $request){
        $deleteid = $request->input('deleteid');
        $deletename = DB::table('admin_news_table')->where('id',$deleteid)->value('title');
        DB::table('admin_news_table')->where('id',$deleteid)->delete();
        return view('admin.deletemessage',compact('deletename'));
    }
    //betページ
    public function bet(Request $request){
        return view('admin.bet');
    }
    public function betregister(Request $request){
        return view('admin.betregister');
    }
    //
    public function betregisterpost(BetregisterRequest $request){

        $title = $request->input('title');
        $question = $request->input('question');
        $answer1 = $request->input('answer1');
        $answer2 = $request->input('answer2');
        $answer3 = $request->input('answer3');
        /*開始日付*/
        $date = $request->input('date');
        $time = $request->input('time');
        /*締切日時*/
        $enddate = $request->input('enddate');
        $endtime = $request->input('endtime');

        $start_date_before = $date .$time;
        $end_date_before = $enddate .$endtime;

        $start_date = date('Y-m-d H:i:s' ,strtotime($start_date_before));
        $end_date = date('Y-m-d H:i:s' ,strtotime($end_date_before));

            $value = [
                'title' => $title,
                'question' => $question,
                'answer1' => $answer1,
                'answer2' => $answer2,
                'answer3' => $answer3,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];

        DB::table('questions')->insert($value);
        return view('admin.betregisterpost');
    }
}
