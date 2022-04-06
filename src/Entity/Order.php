<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 255)]
    public string $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    public User $user;

    #[ORM\Column(type: 'string', length: 1024)]
    public string $address;

    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'order', cascade: ['persist'])]
    public Collection $order_products;

    public function __construct()
    {
        $this->order_products = new ArrayCollection();
    }

    public function getTotal(): int
    {
        $order_products = $this->order_products->getValues();

        $total = 0;

        /** @var OrderProduct $order_product */
        foreach ($order_products as $order_product) {
            $total += $order_product->getTotal();
        }

        return $total;
    }

    public function getTotalUsingProductPrice(): int
    {
        $order_products = $this->order_products->getValues();

        $total = 0;

        /** @var OrderProduct $order_product */
        foreach ($order_products as $order_product) {
            $total += $order_product->getTotalUsingProductPrice();
        }

        return $total;
    }

    public function isEmpty(): bool
    {
        return $this->order_products->count() === 0;
    }
}
