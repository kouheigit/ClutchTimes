<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // カード番号からスペースを削除
        if ($this->has('card_number') && $this->card_number) {
            $this->merge([
                'card_number' => str_replace(' ', '', $this->card_number),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment' => 'required|integer|in:0,1',
            'note' => 'nullable|string|max:500',
            'card_number' => 'required_if:payment,1|nullable|string|regex:/^[0-9]{13,16}$/',
            'card_expire' => 'required_if:payment,1|nullable|string|regex:/^[0-9]{2}\/[0-9]{2}$/',
            'security_code' => 'required_if:payment,1|nullable|string|regex:/^[0-9]{3,4}$/',
            'token' => 'nullable|string',
            'use_point' => 'nullable|integer|min:0',
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
            'payment.required' => '決済方法を選択してください',
            'payment.in' => '決済方法が不正です',
            'note.max' => '備考は500文字以内で入力してください',
            'card_number.required_if' => 'クレジットカード番号を入力してください',
            'card_number.regex' => 'クレジットカード番号の形式が正しくありません',
            'card_expire.required_if' => '有効期限を入力してください',
            'card_expire.regex' => '有効期限はMM/YY形式で入力してください',
            'security_code.required_if' => 'セキュリティコードを入力してください',
            'security_code.regex' => 'セキュリティコードは3桁または4桁の数字で入力してください',
            'use_point.integer' => '利用ポイントは数値で入力してください',
            'use_point.min' => '利用ポイントは0以上で入力してください',
        ];
    }
}
