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
        /*画像のバリデーション未装着*/
        return [
            'name'=>'required|max:20',
            'postal_code'=>'required|regex:/^\d{3}-\d{4}$/',
            'address'=>'required',
        ];
    }

    public function messages()
    {
        return[
            'name.required'=>'ユーザー名を入力してください',
            'name.max'=>'ユーザー名は20文字以内で入力してください',
            'postal_code.required'=>'郵便番号を入力してください',
            'postal_code.regex'=>'郵便番号は3桁-4桁の数字で入力してください',
            'address.required'=>'住所を入力してください',
            ];
    }
}
