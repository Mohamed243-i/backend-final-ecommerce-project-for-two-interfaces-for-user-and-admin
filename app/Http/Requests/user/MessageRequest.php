<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    { {
            return [
                    'userEmail' => ['required', 'email'],
                    'userName' => ['required','max:50'],
                    'userMessage'=>['required','max:150','min:5'],


            ];
        }
    }
   
}
