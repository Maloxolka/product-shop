<?php

declare(strict_types=1);

namespace App\DTO;

/**
 * @property OrderProductDTO[] $order_product_dtos
 */
class OrderDTO
{
    public function __construct(
        public string $status,
        public string $address,
        public array $order_product_dtos,
    ) {
    }
}
