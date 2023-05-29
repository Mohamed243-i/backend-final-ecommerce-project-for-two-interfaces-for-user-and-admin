<?php

namespace App\Http\Requests\products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
    {
        return [
            'name' => ['required','min:4','unique:products,name'],
            'rate' => 'required',
            'price' =>'required|numeric',
            'quantity' => ['required'],
            'description' => 'required',
            'discount' => 'required',
            'category_id' => 'required',
            // 'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image'=>'required',
            // 'image' => 'required|mimetypes:image/jpeg,image/png|max:2048',
        ];
    }
}
