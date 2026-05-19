<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    // バリデーションルール
    public function rules()
    {
        return [
            'last_name_kana' => [
                'required',
                'max:30',
                'regex:/^[ァ-ヶー]+$/u',
            ],
            'first_name_kana' => [
                'required',
                'max:30',
                'regex:/^[ァ-ヶー]+$/u',
            ],
            'last_name_kanji' => ['required', 'max:30'],
            'first_name_kanji' => ['required', 'max:30'],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => ['required', 'min:7', 'max:50'],
        ];
    }

    // エラーメッセージ
    public function messages()
    {
        return [
            'last_name_kana.required' => 'セイは必ず入力してください。',
            'last_name_kana.regex' => 'セイは全角カタカナで入力してください。',
            'last_name_kana.max' => 'セイは30文字以内で入力してください。',

            'first_name_kana.required' => 'メイは必ず入力してください。',
            'first_name_kana.regex' => 'メイは全角カタカナで入力してください。',
            'first_name_kana.max' => 'メイは30文字以内で入力してください。',

            'last_name_kanji.required' => '姓は必ず入力してください',
            'last_name_kanji.max' => '姓は30文字以内で入力してください。',

            'first_name_kanji.required' => '名は必ず入力してください',
            'first_name_kanji.max' => '名は30文字以内で入力してください。',

            'email.required' => 'メールアドレスは必ず入力してください',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',

            'password.required' => 'パスワードは必ず入力してください',
            'password.min' => 'パスワードは7文字以上で入力してください。',
            'password.max' => 'パスワードは50文字以内で入力してください。',
        ];
    }
}