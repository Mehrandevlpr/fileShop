<?php

namespace App\Http\Requests\admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            // our fields ...
            'title' => 'max:128|min:3|required',
            'slug'  =>  'max:128|min:3|required'
        ];
    }
}
