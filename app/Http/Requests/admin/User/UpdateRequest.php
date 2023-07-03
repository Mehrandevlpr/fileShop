<?php

namespace App\Http\Requests\Admin\User;

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
            'name'    =>  'required|max:128|min:3',
            'email'   =>  'required|max:128|min:10|unique:users,email,'. $this->request->get('user_id') .'',
            'mobile'  =>  'required|digits:11|unique:users,mobile,'. $this->request->get('user_id') .'',
            'role'    =>  'required|in:admin,user,seller'
        ];
    }
}
