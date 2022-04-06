<?php

declare(strict_types=1);

namespace App\Formatters\Dictionaries;

use App\Dictionaries\OrderStatuses\OrderStatus;

class OrderStatusFormatter
{
    /**
     * @param OrderStatus[] $order_statuses
     */
    public function formatArray(array $order_statuses): array
    {
        $result = [];

        foreach ($order_statuses as $order_status) {
            $result[] = $this->format($order_status);
        }

        return $result;
    }

    public function format(OrderStatus $order_status): array
    {
        return [
            'id' => $order_status->id,
            'name' => $order_status->name,
        ];
    }
}
