<?php

declare(strict_types=1);

namespace App\Dictionaries\OrderStatuses;

class OrderStatusDictionary
{
    public const CART = 'cart';
    public const COLLECTING = 'collecting';
    public const IN_DELIVERY = 'in_delivery';
    public const COMPLETE = 'complete';

    public function getVisibleIds(): array
    {
        return [
            self::COLLECTING,
            self::IN_DELIVERY,
            self::COMPLETE,
        ];
    }

    public function all(): array
    {
        return [
            new OrderStatus(self::CART, 'Корзина'),
            new OrderStatus(self::COLLECTING, 'Собирается'),
            new OrderStatus(self::IN_DELIVERY, 'В пути'),
            new OrderStatus(self::COMPLETE, 'Доставлен'),
        ];
    }

    public function getById(string $id): OrderStatus
    {
        $statuses_with_id = array_filter($this->all(), fn (OrderStatus $entity) => $entity->id === $id);

        return array_values($statuses_with_id)[0];
    }
}
