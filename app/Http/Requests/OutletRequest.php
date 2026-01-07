<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OutletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $outletId = $this->route('outlet')->OutletID ?? $this->input('OutletID');

        $rules = [
            'OutletCode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('Outlets', 'OutletCode')
                    ->where('IsDeleted', 0)
                    ->ignore($outletId, 'OutletID'),
            ],
            'OutletName' => 'required|string|max:100',
            'IPAddress' => 'required|string|max:50',
            'City' => 'nullable|string|max:100',
            'District' => 'nullable|string|max:100',
            'OpeningDate' => 'required|date',
            'ClosingDate' => 'nullable|date|after_or_equal:OpeningDate',
            'Status' => 'required|in:Active,Inactive',
            'DatabaseName' => 'required|string|max:100',
            'DatabaseUser' => 'required|string|max:100',
        ];

        // Password required only for new outlets
        if ($this->isMethod('post')) {
            $rules['DatabasePassword'] = 'required|string';
        } else {
            $rules['DatabasePassword'] = 'nullable|string';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'OutletCode.required' => 'Outlet Code is required.',
            'OutletCode.unique' => 'This Outlet Code already exists.',
            'OutletName.required' => 'Outlet Name is required.',
            'IPAddress.required' => 'IP Address is required.',
            'OpeningDate.required' => 'Opening Date is required.',
            'ClosingDate.after_or_equal' => 'Closing Date must be after Opening Date.',
            'DatabaseName.required' => 'Database Name is required.',
            'DatabaseUser.required' => 'Database User is required.',
            'DatabasePassword.required' => 'Database Password is required.',
        ];
    }
}
