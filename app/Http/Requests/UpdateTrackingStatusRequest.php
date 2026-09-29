<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrackingStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tracking_number' => ['required', 'string', 'exists:shipments,tracking_number'],
            'branch_id'       => ['nullable', 'integer', 'exists:branches,id'],
            'status'          => ['required', 'string', 'in:MANIFEST,ON_TRANSIT,OUT_FOR_DELIVERY,DELIVERED'],
            'description'     => ['required', 'string', 'max:255'],
        ];
    }
}