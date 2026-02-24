<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CategoryRequest — Validation for category CRUD
 */
class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category') ?? $this->route('id');

        return [
            'name'        => 'required|string|max:255|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique'   => 'Nama kategori sudah ada.',
            'image.image'   => 'File harus berupa gambar.',
            'image.mimes'   => 'Format gambar: jpeg, jpg, png.',
            'image.max'     => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
