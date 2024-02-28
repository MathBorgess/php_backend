<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Transaction;

class StoreTransactionRequest extends FormRequest
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
            'value' => 'required|numeric',
            'type' => Rule::in(Transaction::transactionTypes()),
            'category_id' => 'required|exists:transaction_categories,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}