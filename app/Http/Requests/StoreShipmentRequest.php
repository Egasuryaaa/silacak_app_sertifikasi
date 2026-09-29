<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id'       => ['nullable', 'integer', 'exists:customers,id'],
            'origin_branch_id'  => ['required', 'integer', 'exists:branches,id'],
            'dest_branch_id'    => ['required', 'integer', 'exists:branches,id'],
            'sender_name'       => ['required', 'string', 'max:100'],
            'sender_phone'      => ['required', 'string', 'max:20'],
            'receiver_name'     => ['required', 'string', 'max:100'],
            'receiver_phone'    => ['required', 'string', 'max:20'],
            'receiver_address'  => ['required', 'string'],
            'service_code'      => ['required', 'string', 'in:REG,EXP,KGO,SD'],
            'actual_weight'     => ['required', 'numeric', 'min:0.1'],
            'length_cm'         => ['required', 'integer', 'min:1'],
            'width_cm'          => ['required', 'integer', 'min:1'],
            'height_cm'         => ['required', 'integer', 'min:1'],
            'goods_value'       => ['nullable', 'numeric', 'min:0'],
        ];
    }
}