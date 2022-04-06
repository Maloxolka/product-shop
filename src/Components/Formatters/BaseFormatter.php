<?php

declare(strict_types=1);

namespace App\Components\Formatters;

use App\Components\Traits\ConvertPriceTrait;

class BaseFormatter
{
    use ConvertPriceTrait;

    protected function formatPrice(int $price): array
    {
        $price_float = $this->fromDbValueToMoney($price);

        return [
            'value' => $price_float,
            'formatted' => $price_float . ' руб.',
        ];
    }
}
