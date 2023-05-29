<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation;
use Illuminate\Validation\Rule;

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
        $userId = auth()->user()->id;
        return [
            'name' => ['required','unique:users,name,' . $userId,
            // Rule::unique('users:name')->ignore($userId)
        ],
            'email' => ['required','email',$this->id,
            Rule::unique('users')->ignore($userId)
        ],
            'gender' => ['required'],
            // 'image' => ['required'],
        ];
    }
}
