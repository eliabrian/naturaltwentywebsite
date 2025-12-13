<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => [
                'required',
                'numeric',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/',
            ],
            'room_id' => 'required|exists:rooms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'eta' => 'required',
            'total_person' => [
                'nullable', 'integer', 'min:1', 'max:8',
            ],
            'need_dm' => 'boolean',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
