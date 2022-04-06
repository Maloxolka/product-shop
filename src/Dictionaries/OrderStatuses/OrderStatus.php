<?php

declare(strict_types=1);

namespace App\Dictionaries\OrderStatuses;

class OrderStatus
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
