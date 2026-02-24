<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller1 = User::where('email', 'seller1@furnishop.com')->first();
        $seller2 = User::where('email', 'seller2@furnishop.com')->first();

        $products = [
            // Seller 1 - Sofa & Kursi
            [
                'user_id' => $seller1->id,
                'category_id' => 1,
                'name' => 'Kursi Belajar Ergonomis',
                'price' => 850000,
                'stock' => 20,
                'description' => 'Kursi belajar ergonomis cocok untuk mahasiswa yang sering begadang ngerjain tugas. Sandaran punggung nyaman, bahan breathable mesh, dan tinggi bisa diatur.',
                'material' => 'Metal & Mesh Fabric',
                'color' => 'Hitam',
                'dimensions' => '60x55x90 cm',
                'weight' => 8,
                'image' => 'products/kursi-belajar-ergonomis.jpg',
            ],
            // Seller 1 - Meja
            [
                'user_id' => $seller1->id,
                'category_id' => 2,
                'name' => 'Meja Belajar Minimalis',
                'price' => 650000,
                'stock' => 15,
                'description' => 'Meja belajar minimalis ukuran pas buat kamar kos. Ada laci kecil buat nyimpen alat tulis dan slot kabel buat charger laptop. Anti ribet!',
                'material' => 'Particle Board',
                'color' => 'Oak Natural',
                'dimensions' => '100x50x75 cm',
                'weight' => 12,
                'image' => 'products/meja-belajar-minimalis.jpg',
            ],
            // Seller 1 - Lemari & Rak
            [
                'user_id' => $seller1->id,
                'category_id' => 3,
                'name' => 'Rak Buku Susun 4 Tingkat',
                'price' => 450000,
                'stock' => 25,
                'description' => 'Rak buku 4 tingkat buat numpuk buku kuliah, novel, sama koleksi manga. Material kayu pinus kokoh, gampang dirakit sendiri tanpa perlu tukang.',
                'material' => 'Kayu Pinus',
                'color' => 'Natural',
                'dimensions' => '60x30x120 cm',
                'weight' => 10,
                'image' => 'products/rak-buku-susun-4-tingkat.jpg',
            ],
            // Seller 1 - Tempat Tidur
            [
                'user_id' => $seller1->id,
                'category_id' => 4,
                'name' => 'Ranjang Single Bed',
                'price' => 1200000,
                'stock' => 8,
                'description' => 'Ranjang single bed ukuran 90x200 cm, pas buat kamar kos yang sempit. Rangka besi kokoh, ada kolong buat naruh koper atau box penyimpanan.',
                'material' => 'Metal Frame',
                'color' => 'Putih',
                'dimensions' => '90x200x80 cm',
                'weight' => 20,
                'image' => 'products/ranjang-single-bed.jpg',
            ],
            // Seller 2 - Sofa & Kursi
            [
                'user_id' => $seller2->id,
                'category_id' => 1,
                'name' => 'Bean Bag Santai',
                'price' => 350000,
                'stock' => 30,
                'description' => 'Bean bag empuk buat rebahan sambil nonton drakor atau main game. Bahan kanvas tebal anti sobek, isi styrofoam premium yang nggak kempes.',
                'material' => 'Kanvas & Styrofoam',
                'color' => 'Abu-abu',
                'dimensions' => '80x80x90 cm',
                'weight' => 5,
                'image' => 'products/bean-bag-santai.jpg',
            ],
            // Seller 2 - Meja
            [
                'user_id' => $seller2->id,
                'category_id' => 2,
                'name' => 'Meja Laptop Lipat Portable',
                'price' => 250000,
                'stock' => 35,
                'description' => 'Meja laptop lipat portable, bisa dipake di kasur atau di lantai. Ada fan holder biar laptop nggak overheat pas lagi ngoding atau main game.',
                'material' => 'Aluminium & MDF',
                'color' => 'Hitam',
                'dimensions' => '60x40x28 cm',
                'weight' => 2,
                'image' => 'products/meja-laptop-lipat-portable.jpg',
            ],
            // Seller 2 - Lemari & Rak
            [
                'user_id' => $seller2->id,
                'category_id' => 3,
                'name' => 'Rak Dinding Minimalis',
                'price' => 180000,
                'stock' => 40,
                'description' => 'Rak dinding minimalis buat pajang tanaman, foto, atau buku tipis. Tinggal tempel di tembok kamar kos, hemat tempat dan bikin kamar aesthetic.',
                'material' => 'Kayu Pinus',
                'color' => 'Natural',
                'dimensions' => '60x15x20 cm',
                'weight' => 2,
                'image' => 'products/rak-dinding-minimalis.jpg',
            ],
            // Seller 2 - Tempat Tidur
            [
                'user_id' => $seller2->id,
                'category_id' => 4,
                'name' => 'Ranjang Kayu Palet',
                'price' => 550000,
                'stock' => 10,
                'description' => 'Ranjang dari kayu palet recycled, vibes industrial aesthetic. Kuat, murah, dan gampang dirakit. Cocok buat anak kos yang suka DIY dan ramah lingkungan.',
                'material' => 'Kayu Palet',
                'color' => 'Natural',
                'dimensions' => '100x200x30 cm',
                'weight' => 18,
                'image' => 'products/ranjang-kayu-palet.jpg',
            ],
        ];

        foreach ($products as $data) {
            $imageUrl = $data['image'];
            unset($data['image']);

            $data['slug'] = Str::slug($data['name']);
            $data['is_active'] = true;

            $product = Product::create($data);

            // Create product image with Unsplash URL
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imageUrl,
            ]);
        }
    }
}
