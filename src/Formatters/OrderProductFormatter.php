<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Components\Formatters\BaseFormatter;
use App\Entity\OrderProduct;

class OrderProductFormatter extends BaseFormatter
{
    /**
     * @param OrderProduct[] $order_products
     */
    public function formatArray(array $order_products): array
    {
        $result = [];

        foreach ($order_products as $order_product) {
            $result[] = $this->format($order_product);
        }

        return $result;
    }

    public function format(OrderProduct $order_product): array
    {
        return array_merge($this->baseFormat($order_product), [
            'price' => $this->formatPrice($order_product->price),
            'total' => $this->formatPrice($order_product->getTotal()),
        ]);
    }

    /**
     * @param OrderProduct[] $order_products
     */
    public function formatArrayForCart(array $order_products): array
    {
        $result = [];

        foreach ($order_products as $order_product) {
            $result[] = $this->formatForCart($order_product);
        }

        return $result;
    }

    public function formatForCart(OrderProduct $order_product): array
    {
        return array_merge($this->baseFormat($order_product), [
            'price' => $this->formatPrice($order_product->product->price),
            'total' => $this->formatPrice($order_product->getTotalUsingProductPrice()),
        ]);
    }

    private function baseFormat(OrderProduct $order_product): array
    {
        return [
            'id' => $order_product->id,
            'amount' => $order_product->amount,
            'product' => (new ProductFormatter())->format($order_product->product),
        ];
    }
}
