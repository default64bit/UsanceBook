<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FriendsRequest extends FormRequest
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
