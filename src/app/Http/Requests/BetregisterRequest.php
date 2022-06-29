<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BetregisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                'title' =>'between:0,100',
                'question'=>'between:0,1000',
                 'answer1'=>'between:0,100',
                 'answer2'=>'between:0,100',
                 'answer3'=>'between:0,100',
        ];
    }
    public function messages(){
        return [
            'title.between'=>'【試合情報の投稿に失敗しました】試合情報の文字数は0~100文字以内で入力してください',
            'question.between'=>'【予想問題の投稿に失敗しました】予想問題の文字数は0~1000文字以内で入力してください',
            'answer1.between'=>'選択肢1の投稿に失敗しました】選択肢1の文字数は0~100文字以内で入力してください',
            'answer2.between'=>'選択肢2の投稿に失敗しました】選択肢2の文字数は0~100文字以内で入力してください',
            'answer3.between'=>'選択肢3の投稿に失敗しました】選択肢3の文字数は0~100文字以内で入力してください',
            ];
    }
}
