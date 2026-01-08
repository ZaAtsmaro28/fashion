<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Izinkan semua user yang terautentikasi (Kasir/Admin) untuk melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk transaksi checkout.
     */
    public function rules(): array
    {
        return [
            // Pastikan ada array barang yang dikirim
            'items' => 'required|array|min:1',

            // Validasi tiap item di dalam array
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',

            // Validasi pembayaran
            'total_pay' => 'required|numeric|min:0',

            // Opsional: Jika ingin mencatat nama customer atau catatan tambahan
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Pesan error kustom agar lebih informatif bagi kasir.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'Keranjang belanja tidak boleh kosong.',
            'items.*.product_variant_id.exists' => 'Salah satu produk tidak ditemukan di database.',
            'items.*.quantity.min' => 'Jumlah barang minimal 1.',
            'total_pay.required' => 'Jumlah uang bayar harus diisi.',
            'total_pay.numeric' => 'Format uang bayar harus berupa angka.',
        ];
    }

    /**
     * Menangani kegagalan validasi dan mengembalikan response JSON (bukan redirect).
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422));
    }
}
