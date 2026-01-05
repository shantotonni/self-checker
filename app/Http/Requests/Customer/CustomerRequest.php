<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'name'=>'required|min:3',
            'mobile' => 'required|min:11|max:11',
            //'email'=>'required',
            'area_id'=>'required',
            'district_id'=>'required',
            //'code'=>'required',
            'chassis'=>'required',
            'password'=>'required',
            'product_id'=>'required',
            //'image'=>'required',
            //'address'=>'required',
        ];
    }
}
