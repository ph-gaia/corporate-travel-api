<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TravelOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:departure_date',
        ];
    }
}
