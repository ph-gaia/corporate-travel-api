<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTravelOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,canceled'
        ];
    }
}
