<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CheckoutRequest — Validation for checkout
 */
class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_name'        => 'required|string|max:255',
            'shipping_phone'       => 'required|string|max:20',
            'shipping_address'     => 'required|string',
            'shipping_city'        => 'required|string|max:100',
            'shipping_province'    => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'bank_id'              => 'required|exists:banks,id',
            'notes'                => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_name.required'        => 'Nama penerima wajib diisi.',
            'shipping_phone.required'       => 'Nomor telepon wajib diisi.',
            'shipping_address.required'     => 'Alamat pengiriman wajib diisi.',
            'shipping_city.required'        => 'Kota wajib diisi.',
            'shipping_province.required'    => 'Provinsi wajib diisi.',
            'shipping_postal_code.required' => 'Kode pos wajib diisi.',
            'bank_id.required'              => 'Pilih bank tujuan transfer.',
            'bank_id.exists'                => 'Bank yang dipilih tidak valid.',
        ];
    }
}
