<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create Court Request
 * Validates court creation data
 */
class CreateCourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        // This is an API request, authorization is checked in controller
        return true;
    }

    public function rules(): array
    {
        return [
            'partner_id' => ['required', 'exists:partners,id'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'partner_id.required' => 'Partner ID is required',
            'partner_id.exists' => 'Partner not found',
            'name.required' => 'Court name is required',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'latitude.required' => 'Latitude is required',
            'latitude.between' => 'Latitude must be between -90 and 90',
            'longitude.required' => 'Longitude is required',
            'longitude.between' => 'Longitude must be between -180 and 180',
        ];
    }
}
