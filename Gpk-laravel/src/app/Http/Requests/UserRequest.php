<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $user_id = $this->route('user') ?? \Illuminate\Support\Facades\Auth::id();
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user_id,
            'tel' => 'nullable|string|max:20',
            'last_name' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_kana' => 'nullable|string|max:255',
            'first_kana' => 'nullable|string|max:255',
            'zip1' => 'nullable|string|max:3',
            'zip2' => 'nullable|string|max:4',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
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
            'name.required' => '氏名を入力してください',
            'name.max' => '氏名は255文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスの形式が正しくありません',
            'email.unique' => 'このメールアドレスは既に登録されています',
            'tel.max' => '電話番号は20文字以内で入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワードが一致しません',
        ];
    }
}
