<?php

namespace App\Http\Requests\Dealer;

use Illuminate\Foundation\Http\FormRequest;

class DealerStoreRequest extends FormRequest
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
            'area_id'=>'required',
            'dealer_name'=>'required',
            'address'=>'required',
            'mobile'=>'required',
            'lat'=>'required',
            'long'=>'required',
            'image' => 'required|min:jpeg,jpg,png,svg'
        ];
    }
}
