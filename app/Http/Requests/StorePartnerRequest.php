<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StorePartnerRequest extends FormRequest
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
            'business_name'=>'required|string',
            'category_id'=>'required|string',
            'other_categroy_id'=>'required|array',
            'social_provider'=>'required|string',
            'social_url'=>'required|string',
            'business_type'=>'required|string',
            'about_us_survey'=>'required|string',
            'weekends'=>'array',
            'address_address'=>'string',
            'bio'=>'string',
            'address_latitude'=>'numeric',
            'address_longitude'=>'numeric',
        ];
    }

    public function failedValidation(Validator $validator)

    {

        throw new HttpResponseException(response()->json([

            'success'   => false,

            'message'   => 'Validation errors',

            'data'      => $validator->errors()

        ]));
    }
}
