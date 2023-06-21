<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'max:128|min:3|required|unique:categories,title,'.$this->request->get('category_id').'',
            'slug'  =>  'max:128|min:3|required|unique:categories,slug,'.$this->request->get('category_id').''
        ];
    }
}
