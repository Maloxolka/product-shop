<?php

declare(strict_types=1);

namespace App\DTO\Factories;

use App\Components\Requests\BaseRequest;
use App\DTO\OrderDTO;
use App\DTO\OrderProductDTO;

class OrderDTOFactory
{
    public function fromRequest(BaseRequest $request): OrderDTO
    {
        return new OrderDTO(
            $request->getString('status'),
            $request->getString('address'),
            $this->getOrderProductDTOsFromRequest($request),
        );
    }

    private function getOrderProductDTOsFromRequest(BaseRequest $request): array
    {
        $dtos = [];

        foreach ($request->get('order_products') as $order_product) {
            $dtos[] = new OrderProductDTO(
                (int) $order_product['product_id'],
                (int) $order_product['amount'],
                (float) $order_product['price'],
            );
        }

        return $dtos;
    }
}
