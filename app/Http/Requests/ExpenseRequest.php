<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/|min:2'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => translate("the name is required"),
            'price.required' => translate("the price is required"),
            'price.regex' => translate("the price should be a number"),
        ];
    }
}
