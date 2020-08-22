<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRequest extends FormRequest
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

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'error' => $validator->errors()->first(),
                'error_type' => array_keys($validator->errors()->messages())[0],
            ],422)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'name' => 'required|max:255|string',
            'amount' => 'required|numeric|digits_between:1,30',
            'type' => 'required|in:+,-',
            'date' => 'required|max:255|string',
            'transaction_group' => 'sometimes|max:255|numeric|exists:groups,id',
            'for_user' => 'sometimes|max:255|numeric|exists:users,id',
            'card' => 'sometimes|max:255|numeric|exists:cards,id',
        ];

        switch($this->method()){
            case 'POST':
                break;
            case 'PUT':
                break;
        }
        
        return $rule;
    }
}
