<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name'        => 'sometimes|required|string|max:255',
            'sku'         => 'sometimes|required|string|unique:products,sku,' . $this->route('product'),
            'price'       => 'sometimes|required|numeric|min:0',
            'unit'        => 'sometimes|required|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:5048',
        ];
    }
}
