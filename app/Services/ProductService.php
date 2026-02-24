<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * ProductService — Business logic for products
 */
class ProductService implements ProductServiceInterface
{
    // Implementing ProductServiceInterface
    // ...existing code...
    /**
     * Create a new product and handle images
     */
    public function store(array $data, array $images = [])
    {
        $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);

        $product = Product::create($data);

        $this->handleImageUploads($product, $images);

        return $product;
    }

    /**
     * Update existing product and handle images
     */
    public function update($product, array $data, array $images = [], array $deleteImageIds = [])
    {
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::random(5);
        }

        $product->update($data);

        // Delete selected images
        if (!empty($deleteImageIds)) {
            $this->deleteImages($product, $deleteImageIds);
        }

        $this->handleImageUploads($product, $images);

        return $product;
    }

    /**
     * Soft-delete product and remove images from storage
     */
    public function delete($product)
    {
        foreach ($product->productImages as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $product->productImages()->delete();
        $product->delete();
    }

    /**
     * Handle multiple image uploads
     */
    private function handleImageUploads(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            if (!$image instanceof UploadedFile) continue;

            $path = $image->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
            ]);
        }
    }

    /**
     * Delete specific product images
     */
    private function deleteImages(Product $product, array $imageIds): void
    {
        $images = $product->productImages()->whereIn('id', $imageIds)->get();

        foreach ($images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }
    }
}
