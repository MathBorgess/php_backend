<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizeRequest extends FormRequest
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
            'response_type' => 'required|string',
            'client_id' => 'required|string',
            'redirect_uri' => 'required|string',
            'nonce' => 'required|string',
            'state' => 'required|string',
            'code_challenge' => 'required|string',
            'code_challenge_method' => 'required|string',
        ];
    }
}
