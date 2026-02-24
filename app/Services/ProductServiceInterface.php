<?php

namespace App\Services;

interface ProductServiceInterface
{
    public function store(array $data, array $images = []);
    public function update($product, array $data, array $images = [], array $deleteImageIds = []);
    public function delete($product);
}
