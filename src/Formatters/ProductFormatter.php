<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Components\Formatters\BaseFormatter;
use App\Entity\Product;

class ProductFormatter extends BaseFormatter
{
    /**
     * @param Product[] $products
     */
    public function formatArray(array $products): array
    {
        $products_as_array = [];

        foreach ($products as $product) {
            $products_as_array[] = $this->format($product);
        }

        return $products_as_array;
    }

    public function format(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $this->formatPrice($product->price),
            'amount' => $product->amount,
            'file_link' => [
                'url' => $product->file_link,
                'default' => '/images/product.jpg',
            ],
        ];
    }
}
