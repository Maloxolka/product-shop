<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Components\Formatters\BaseFormatter;
use App\Dictionaries\OrderStatuses\OrderStatusDictionary;
use App\Dictionaries\OrderStatuses\OrderStatus;
use App\Entity\Order;

class OrderFormatter extends BaseFormatter
{
    /**
     * @param Order[] $orders
     */
    public function formatArray(array $orders): array
    {
        $result = [];

        foreach ($orders as $order) {
            $result[] = $this->format($order);
        }

        return $result;
    }

    public function format(Order $order): array
    {
        return [
            'id' => $order->id,
            'status' => $this->formatStatus((new OrderStatusDictionary())->getById($order->status)),
            'address' => $order->address,
            'order_products' => (new OrderProductFormatter())->formatArray($order->order_products->getValues()),
            'total' => $this->formatPrice($order->getTotal()),
        ];
    }

    public function formatForCart(Order $order): array
    {
        if ($order->isEmpty()) {
            return [
                'empty' => true,
            ];
        }

        return [
            'id' => $order->id,
            'order_products' => (new OrderProductFormatter())->formatArrayForCart($order->order_products->getValues()),
            'total' => $this->formatPrice($order->getTotalUsingProductPrice()),
            'empty' => false,
        ];
    }

    private function formatStatus(OrderStatus $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
        ];
    }
}
