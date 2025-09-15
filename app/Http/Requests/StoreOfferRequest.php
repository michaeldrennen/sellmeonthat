<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        // User must be logged in, have the 'retailer' role, and have a business profile.
        return auth()->check() &&
               auth()->user()->roles()->where('slug', 'retailer')->exists() &&
               auth()->user()->businessProfile()->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => 'required|numeric|min:0.01',
            'message' => 'nullable|string|max:1000',
        ];
    }
}
