<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'payment_status_id' => 'required|exists:payment_statuses,id',
            'order_id' => 'required|exists:orders,id',
            'payment_provider_id' => 'required|exists:payment_providers,id',
            'amount' => 'required|numeric|min:0',
            'paid_at' => 'nullable|date',
            'method' => 'required|string|max:255',
            'checkout_url' => 'nullable|url',
            'provider_transaction_id' => 'nullable|string|max:255',
            'provider_state' => 'nullable|string|max:255',
        ];
    }
}
