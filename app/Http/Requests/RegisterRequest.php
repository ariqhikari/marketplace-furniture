<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * RegisterRequest — Validation for user registration
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'nullable|string|max:20',
            'role'              => 'required|in:USER,SELLER',
            'password'          => 'required|string|min:6|confirmed',
            'store_name'        => 'required_if:role,SELLER|nullable|string|max:255',
            'store_description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Nama lengkap wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah terdaftar.',
            'role.required'          => 'Pilih role terlebih dahulu.',
            'role.in'                => 'Role tidak valid.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
            'store_name.required_if' => 'Nama toko wajib diisi untuk seller.',
        ];
    }
}
