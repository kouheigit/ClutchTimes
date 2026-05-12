<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceOrderRequest extends FormRequest
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
            'service_id' => 'required|exists:services,id',
            'service_option_id' => 'nullable|exists:service_options,id',
            'quantity' => 'required|integer|min:1|max:100',
            'reservation_id' => 'nullable|exists:reservations,id',
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
            'service_id.required' => 'サービスを選択してください',
            'service_id.exists' => '選択されたサービスが見つかりません',
            'service_option_id.exists' => '選択されたオプションが見つかりません',
            'quantity.required' => '数量を入力してください',
            'quantity.integer' => '数量は数値で入力してください',
            'quantity.min' => '数量は1以上で入力してください',
            'quantity.max' => '数量は100以下で入力してください',
            'reservation_id.exists' => '選択された予約が見つかりません',
        ];
    }
}
