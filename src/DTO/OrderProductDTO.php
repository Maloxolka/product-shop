<?php

declare(strict_types=1);

namespace App\DTO;

class OrderProductDTO
{
    public function __construct(
        public int $product_id,
        public int $amount,
        public float $price,
    ) {
    }
}
