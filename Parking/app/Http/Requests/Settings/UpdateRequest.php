<?php

namespace App\Http\Requests\Settings;

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
            'price_hour' => ['required', 'numeric', 'integer', 'min:1'],
            'token_expiration' => ['required', 'numeric', 'integer', 'min:5', 'max:60'],
        ];
    }
}
