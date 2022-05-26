<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class tosController extends Controller
{
    public function index()
    {
        $checkvalue = null;
        return view('tos', compact('checkvalue'));
    }

    public function tospost(Request $request)
    {
        $checkvalue = $request->input('checked');
        if ($checkvalue == null) {
            $checkvalue = "チェックは必須項目です。";
            return view('tos', compact('checkvalue'));
        } else {
            //新規登録ページにSESSIONで渡す
            session_start();
            $_SESSION['checkvalue'] = $checkvalue;
            return redirect('register');
            //ここからSESSIONの値をRegisterを司っているコントローラーにSESSIONの値を渡す
        }
        //return view('tos',compact('checkvalue'));
    }
}
