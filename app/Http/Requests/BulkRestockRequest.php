<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkRestockRequest extends FormRequest
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
            // 1. Tambahkan 'max' untuk mencegah payload yang terlalu besar (DoS attack)
            'items' => 'required|array|min:1|max:100',

            // 2. Tambahkan 'distinct' agar tidak ada ID varian yang ganda dalam satu request
            'items.*.product_variant_id' => [
                'required',
                'distinct',
                'exists:product_variants,id'
            ],

            // 3. Batasi juga nilai maksimal input (mencegah typo seperti input 1 juta stok)
            'items.*.quantity_added' => 'required|integer|min:1|max:10000',
        ];
    }
}
