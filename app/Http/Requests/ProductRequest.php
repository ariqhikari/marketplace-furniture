<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ProductRequest — Validation for product CRUD
 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ?? $this->route('id');

        return [
            'name'            => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'description'     => 'required|string|min:50',
            'price'           => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'material'        => 'nullable|string|max:100',
            'color'           => 'nullable|string|max:50',
            'dimensions'      => 'nullable|string|max:100',
            'weight'          => 'nullable|numeric|min:0',
            'is_active'       => 'nullable|boolean',
            'images'          => 'nullable|array|max:5',
            'images.*'        => 'image|mimes:jpeg,jpg,png|max:2048',
            'delete_images'   => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak ditemukan.',
            'description.required' => 'Deskripsi wajib diisi.',
            'description.min'      => 'Deskripsi minimal 50 karakter.',
            'price.required'       => 'Harga wajib diisi.',
            'price.numeric'        => 'Harga harus berupa angka.',
            'stock.required'       => 'Stok wajib diisi.',
            'images.max'           => 'Maksimal 5 gambar.',
            'images.*.image'       => 'File harus berupa gambar.',
            'images.*.mimes'       => 'Format gambar: jpeg, jpg, png.',
            'images.*.max'         => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
