<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Sofa & Kursi',
                'description' => 'Koleksi sofa dan kursi berkualitas untuk kenyamanan ruang tamu Anda.',
                'image' => 'categories/sofa-kursi.jpg',
                'sub_category_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Meja',
                'description' => 'Berbagai jenis meja mulai dari meja makan, meja kerja, hingga meja tamu.',
                'image' => 'categories/meja.jpg',
                'sub_category_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Lemari & Rak',
                'description' => 'Lemari pakaian, lemari buku, dan rak display untuk kebutuhan penyimpanan.',
                'image' => 'categories/lemari-rak.jpg',
                'sub_category_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Tempat Tidur',
                'description' => 'Tempat tidur dan ranjang dengan desain modern dan material berkualitas.',
                'image' => 'categories/tempat-tidur.jpg',
                'sub_category_id' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Satu Ranjang Tidur',
                'description' => 'Satu Tempat tidur dan ranjang dengan desain modern dan material berkualitas.',
                'image' => 'categories/tempat-tidur.jpg',
                'sub_category_id' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            $category['slug'] = Str::slug($category['name']);
            Category::create($category);
        }
    }
}
