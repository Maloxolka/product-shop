<?php

declare(strict_types=1);

namespace App\Services;

use App\Dictionaries\OrderStatuses\OrderStatusDictionary;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Exceptions\NotFoundException;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class CartService
{
    public function __construct(
        private OrderRepository $order_repository,
        private EntityManagerInterface $entity_manager,
        private ProductService $product_service,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     * @throws NotFoundException
     */
    public function addProductWithIdToUserCart(int $product_id, User $user): void
    {
        $order = $this->getCartOrderForUser($user);

        $order_product = $this->getOrderProductByProductId($order, $product_id);

        Assert::greaterThan($order_product->product->amount, 0, 'Product is out of stock');

        $order_product->amount++;
        $order_product->product->amount--;

        $this->entity_manager->persist($order_product);
        $this->entity_manager->flush();
    }

    /**
     * @throws NotFoundException
     */
    public function subtractProductWithIdFromUserCart(int $product_id, User $user): void
    {
        $order = $this->getCartOrderForUser($user);

        $order_product = $this->getOrderProductByProductId($order, $product_id);

        $order_product->amount--;
        $order_product->product->amount++;

        $this->entity_manager->persist($order_product);

        if ($order_product->amount === 0) {
            $this->entity_manager->remove($order_product);
        }

        $this->entity_manager->flush();
    }

    public function getCartOrderForUser(User $user): Order
    {
        $order = $this->order_repository->findOneBy([
            'user' => $user,
            'status' => OrderStatusDictionary::CART,
        ]);

        if (!is_null($order)) {
            return $order;
        }

        $order = new Order();

        $order->user = $user;
        $order->status = OrderStatusDictionary::CART;
        $order->address = '';

        return $order;
    }

    /**
     * @throws NotFoundException
     */
    private function getOrderProductByProductId(Order $order, int $product_id): OrderProduct
    {
        $order_product = $order->order_products
            ->matching(new Criteria(Criteria::expr()->eq('product', $this->product_service->getById($product_id))))
            ->first();

        if ($order_product !== false) {
            /* @var OrderProduct $order_product */
            return $order_product;
        }

        $order_product = new OrderProduct();

        $order_product->order = $order;
        $order_product->amount = 0;
        $order_product->product = $this->product_service->getById($product_id);
        $order_product->price = $order_product->product->price;

        return $order_product;
    }
}
