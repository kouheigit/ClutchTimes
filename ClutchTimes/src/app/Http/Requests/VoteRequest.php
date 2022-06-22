<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;

class VoteRequest extends FormRequest
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
            'user_id'=>'required',
            'question_id'=>'required',
            'answer'=>'required',
        ];
    }

    public function messages(){
        return [
            'user_id.required'=>'この項目への入力は必須です',
            'question_id.required'=>'この項目への入力は必須です',
            'answer.required'=>'この項目への入力は必須です',
        ];
    }
}
