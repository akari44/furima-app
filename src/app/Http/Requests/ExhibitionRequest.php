<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            /*'image' => 'required|mimes:jpeg,png',
            'category' => 'required',
            */
            'condition' => 'required',
            'item_name' => 'required',
            'description' => 'required|max:255',
            'price' => 'required|integer|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->price) {
            $this->merge([
                'price' => str_replace(',', '', $this->price),
            ]);
        }
    }


    public function messages() 
    {
        return[
            'image.required' => '商品画像を選択してください',
            'image.mimes' => '商品画像の拡張子は.jpegか.pngにしてください',
            'category.required' => '商品カテゴリを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'item_name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '商品価格は０円以上で入力してください',
        ];
    }
}
