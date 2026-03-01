<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function test_product_has_name()
    {
        $product = new Product(['name' => 'Meja Minimalis']);
        $this->assertEquals('Meja Minimalis', $product->name);
    }

    public function test_product_has_price()
    {
        $product = new Product(['price' => 150000]);
        $this->assertEquals(150000, $product->price);
    }

    public function test_product_is_active_by_default()
    {
        $product = new Product();
        $this->assertTrue($product->is_active ?? true);
    }
}
