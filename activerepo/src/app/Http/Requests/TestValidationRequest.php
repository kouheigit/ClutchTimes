<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestValidationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>['required','string','max:20'],
            'title'=>['required','string','max:30'],
            'comment'=>['required','string','max:300'],
            //画像バリデーション一時除外
           // 'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.require'=>'名前は入力してください',
            'name.string'=>'名前は文字列で入力してください',
            'name.max'=>'名前は20文字以内にしてください',

            'title.require'=>'コメントを入力してください',
            'title.string'=>'コメントは文字列で入力してください',
            'title.max'=>'タイトルは30文字以内にしてください',

            'comment.require'=>'コメントを入力してください',
            'commnet.string'=>'コメントは文字列で入力してください',
            'comment.max'=>'コメントは300文字以内にしてください',
        ];
    }
}
