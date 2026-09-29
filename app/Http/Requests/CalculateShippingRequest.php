<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalculateShippingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_code'    => ['required', 'string', 'in:REG,EXP,KGO,SD'],
            'actual_weight'   => ['required', 'numeric', 'min:0.1'],
            'length'          => ['required', 'integer', 'min:1'],
            'width'           => ['required', 'integer', 'min:1'],
            'height'          => ['required', 'integer', 'min:1'],
            'goods_value'     => ['nullable', 'numeric', 'min:0'],
            'customer_id'     => ['nullable', 'integer', 'exists:customers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_code.in'     => 'Layanan yang dipilih tidak valid (REG, EXP, KGO, atau SD).',
            'actual_weight.min'   => 'Berat aktual minimal 0.1 kg.',
            'customer_id.exists'  => 'Data member tidak ditemukan dalam sistem.',
        ];
    }
}