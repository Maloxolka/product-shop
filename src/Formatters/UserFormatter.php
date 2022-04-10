<?php

declare(strict_types=1);

namespace App\Formatters;

use App\Components\Formatters\BaseFormatter;
use App\Components\Traits\ConvertPriceTrait;
use App\Entity\Order;
use App\Entity\User;

class UserFormatter extends BaseFormatter
{
    use ConvertPriceTrait;

    public function formatOne(User $user): array
    {
        $order_sum = array_sum(array_map(fn(Order $order) => $order->getTotal(), $user->orders->toArray()));

        return [
            'id' => $user->getId(),
            'email' => $user->email,
            'name' => $user->name,
            'surname' => $user->surname,
            'statistics' => [
                'order_count' => $user->orders->count(),
                'order_sum' => $this->formatPrice($order_sum),
            ],
        ];
    }
}