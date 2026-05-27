<?php

namespace App\Http\Requests;

use App\Rules\CheckCategoryIdRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistProductRequest extends FormRequest
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
            'category_id' => ['required', new CheckCategoryIdRule()],
            'name' => 'required|max:255',
            'price' => 'required|digits_between:1,9',
            'image_path' => 'required',
        ];
    }
}
