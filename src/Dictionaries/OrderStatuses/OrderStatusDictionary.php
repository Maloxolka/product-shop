<?php

declare(strict_types=1);

namespace App\Dictionaries\OrderStatuses;

class OrderStatusDictionary
{
    public const CART = 'cart';
    public const AWAITING_PAYMENT = 'awaiting_payment';
    public const COLLECTING = 'collecting';
    public const IN_DELIVERY = 'in_delivery';
    public const COMPLETE = 'complete';

    public function getVisibleIds(): array
    {
        return [
            self::AWAITING_PAYMENT,
            self::COLLECTING,
            self::IN_DELIVERY,
            self::COMPLETE,
        ];
    }

    public function all(): array
    {
        return [
            new OrderStatus(self::CART, 'Корзина'),
            new OrderStatus(self::AWAITING_PAYMENT, 'Ожидает оплаты'),
            new OrderStatus(self::COLLECTING, 'В процессе сборки'),
            new OrderStatus(self::IN_DELIVERY, 'Отправлен'),
            new OrderStatus(self::COMPLETE, 'Доставлен'),
        ];
    }

    public function getById(string $id): OrderStatus
    {
        $statuses_with_id = array_filter($this->all(), fn (OrderStatus $entity) => $entity->id === $id);

        return array_values($statuses_with_id)[0];
    }
}
