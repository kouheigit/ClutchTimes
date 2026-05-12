<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateRequest extends FormRequest
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
            'checkin_date' => 'required|date|after_or_equal:today',
            'checkout_date' => 'required|date|after:checkin_date',
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
            'checkin_date.required' => 'チェックイン日を入力してください',
            'checkin_date.date' => 'チェックイン日の形式が正しくありません',
            'checkin_date.after_or_equal' => 'チェックイン日は今日以降の日付を選択してください',
            'checkout_date.required' => 'チェックアウト日を入力してください',
            'checkout_date.date' => 'チェックアウト日の形式が正しくありません',
            'checkout_date.after' => 'チェックアウト日はチェックイン日より後の日付を選択してください',
            'adult.required' => '大人の人数を入力してください',
            'adult.min' => '大人は最低1名必要です',
            'adult.max' => '大人は最大10名までです',
            'child.max' => '子供は最大10名までです',
            'dog.max' => '犬は最大5頭までです',
            'note.max' => '備考は500文字以内で入力してください',
        ];
    }
}

