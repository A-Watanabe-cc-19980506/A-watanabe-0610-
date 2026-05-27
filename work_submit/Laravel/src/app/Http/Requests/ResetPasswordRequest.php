<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        $rules = [
            'password' => ['required', 'regex:/^[0-9a-zA-Z-_]{8,32}$/', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
            'reset_token' => ['required', new \App\Rules\TokenExpirationTimeCheck()],
        ];

        return $rules;
    }

    /**
     * エラーメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => 'パスワードを入力してください',
            'password.regex' => 'パスワードは半角英数字とハイフンとアンダーバーのみで8文字以上32文字以内で入力してください',
            'password.confirmed' => 'パスワードが再入力欄と一致していません',
        ];
    }

}
