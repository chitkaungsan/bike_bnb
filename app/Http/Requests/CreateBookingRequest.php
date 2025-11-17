<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
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
            'bike_id' => 'required|exists:bikes,id',
            'user_id' => 'required|exists:users,id',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'required|string|max:20',
            'daily_rate' => 'required|numeric|min:0',
            'days' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'totalPrice' => 'required|numeric|min:0',
            'selectedDates' => 'required|array|size:2',
            'selectedDates.*' => 'required|date',

        ];
    }
}
