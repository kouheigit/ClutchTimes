<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addnewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        /*
        return false;
        */
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
            'comment'=>'between:0,1000',
        ];
    }
    public function messages()
    {
        return [
            'title.between'=>'【タイトルの投稿に失敗しました】タイトルの文字数は0~100文字以内で入力してください',
            'comment.between'=>'【本文の投稿に失敗しました】本文の文字数は1000文字以内でお願いします',
        ];
    }
}
