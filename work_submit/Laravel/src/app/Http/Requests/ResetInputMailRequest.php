<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetInputMailRequest extends FormRequest
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
            'email' => ['required', 'email:rfc,dns,filter',  'exists:users,email']
        ];
    }

    /**
     * エラーメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' =>  "メールアドレスを入力してください",
            'email.email' => "メールアドレスの形式ではありません",
            'email.exists' => "登録しているメールアドレスを入力してください"
        ];
    }
}
