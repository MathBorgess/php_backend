<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class TokenRequest extends FormRequest
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
            'grant_type' => 'required|string',
            'redirect_uri' => 'required|string',
            'state' => 'required|string',
            'code' => 'required|string',
            'code_verifier' => 'required|string',
        ];
    }
}

/**

Especifica para o provedor o tipo de autorização. Neste caso será authorization_code

Código retornado pela requisição anterior (exemplo: Z85qv1)

URI de retorno cadastrada para a aplicação cliente no formato URL Encode. Este parâmetro não pode conter caracteres especiais conforme consta na especificação auth 2.0 Redirection Endpoint

Senha sem criptografia enviada do parâmetro code_challenge presente no Passo 3 */
