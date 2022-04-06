<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductDTO
{
    public ?UploadedFile $file = null;

    public function __construct(
        public string $name,
        public int $amount,
        public float $price_float,
    ) {
    }
}
