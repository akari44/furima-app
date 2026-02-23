<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|max:20',
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string',
            'building_name' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号は3桁-4桁の形式で入力してください',
            'address.required' => '住所を入力してください',
            'address.string' => '住所は文字列で入力してください',
            'avatar.image' => 'プロフィール画像は画像ファイルを選択してください',
            'avatar.mimes' => '使用できる画像形式は jpeg, png, jpg のみです',
            'avatar.max' => '画像サイズは2MB以下にしてください',
        ];
    }

}
