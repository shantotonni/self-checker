<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutletConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'OutletID' => 'required|exists:outlets,OutletID',
            'ConfigType' => 'required|string|max:50',
            'Description' => 'required|string|max:255',
            'SqlScript' => 'required|string',
            'Parameters' => 'nullable|json',
            'Priority' => 'nullable|in:Low,Medium,High,Critical',
            'DatabaseName' => 'nullable|string|max:100',
            'DatabasePort' => 'nullable|string|max:10',
            'DatabaseUser' => 'nullable|string|max:100',
            'DatabasePassword' => 'nullable|string',
            'ScheduledAt' => 'nullable|date',
            'Remarks' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'OutletID.required' => 'Please select an outlet.',
            'OutletID.exists' => 'Selected outlet does not exist.',
            'ConfigType.required' => 'Configuration type is required.',
            'Description.required' => 'Description is required.',
            'SqlScript.required' => 'SQL Script is required.',
            'Parameters.json' => 'Parameters must be valid JSON.',
        ];
    }
}
