<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:gardening,landscaping',
            'homeowner_id' => 'required|exists:users,id',
            'service_ids' => 'sometimes|array',
            'service_ids.*' => 'exists:services,id',
            'gardener_id' => 'required_if:type,gardening|exists:gardeners,id',
            'service_provider_id' => 'required_if:type,landscaping|exists:service_providers,id',
            'address' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'total_price' => 'required|numeric|min:0',
            'special_instructions' => 'nullable|string',
        ];
    }
}