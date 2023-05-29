<?php

namespace App\Http\Requests\user;

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
            'name' => ['required','min:4','unique:users,name'],
            'email' => 'required|email|unique:users,email|max:255',
            'password' =>'required|min:6|max:16',
            'gender' => ['required'],
            'image' => 'required'
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ];

        
    }
    // public function messages()
    // {
    //     return [
    //         'name.required' => 'name is required',
    //         'name.min' => 'name must not less than 4 char',
    //         // 'password' =>'required|min:6|max:16',
    //         // 'gender' => ['required'],
    //         // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
    //     ];

        
    // }
}
