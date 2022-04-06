<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderProductRepository;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'integer')]
    public int $amount;

    #[ORM\Column(type: 'integer')]
    public int $price;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'order_products', cascade: ['persist'])]
    public Order $order;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    public Product $product;

    #[ORM\Column(type: 'integer')]
    public int $product_id;

    public function getTotal(): int
    {
        return $this->price * $this->amount;
    }

    public function getTotalUsingProductPrice(): int
    {
        return $this->product->price * $this->amount;
    }
}
