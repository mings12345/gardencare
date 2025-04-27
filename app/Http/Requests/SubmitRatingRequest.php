<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitRatingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization as needed
    }

    public function rules()
    {
        return [
            'rating' => 'required|numeric|min:0|max:5',
            'feedback' => 'nullable|string|max:255',
        ];
    }
}
