<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'calendar_id' => 'required|exists:calendars,id',
            'adult' => 'required|integer|min:1|max:10',
            'child' => 'nullable|integer|min:0|max:10',
            'dog' => 'nullable|integer|min:0|max:5',
            'note' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'calendar_id.required' => 'カレンダーを選択してください',
            'calendar_id.exists' => '選択されたカレンダーが見つかりません',
            'adult.required' => '大人の人数を入力してください',
            'adult.integer' => '大人の人数は数値で入力してください',
            'adult.min' => '大人は最低1名必要です',
            'adult.max' => '大人は最大10名までです',
            'child.integer' => '子供の人数は数値で入力してください',
            'child.min' => '子供の人数は0名以上で入力してください',
            'child.max' => '子供は最大10名までです',
            'dog.integer' => '犬の頭数は数値で入力してください',
            'dog.min' => '犬の頭数は0頭以上で入力してください',
            'dog.max' => '犬は最大5頭までです',
            'note.max' => '備考は500文字以内で入力してください',
        ];
    }
}
