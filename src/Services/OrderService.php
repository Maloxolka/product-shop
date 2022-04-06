<?php

declare(strict_types=1);

namespace App\Services;

use App\Components\Traits\ConvertPriceTrait;
use App\Dictionaries\OrderStatuses\OrderStatusDictionary;
use App\DTO\OrderDTO;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Exceptions\NotFoundException;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

class OrderService
{
    use ConvertPriceTrait;

    public function __construct(
        private OrderRepository $order_repository,
        private CartService $cart_service,
        private EntityManagerInterface $entity_manager,
    ) {
    }

    /**
     * @return Order[]
     */
    public function getUserList(User $user): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('user', $user))
            ->andWhere(Criteria::expr()->in('status', (new OrderStatusDictionary())->getVisibleIds()))
        ;

        return $this->order_repository->matching($criteria)->getValues();
    }

    /**
     * @return Order[]
     */
    public function getAdminList(): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->in('status', (new OrderStatusDictionary())->getVisibleIds()))
        ;

        return $this->order_repository->matching($criteria)->getValues();
    }

    public function formUserOrder(User $user, string $address): Order
    {
        $order = $this->cart_service->getCartOrderForUser($user);

        Assert::greaterThan($order->order_products->count(), 0);

        $order->status = OrderStatusDictionary::AWAITING_PAYMENT;
        $order->address = $address;

        $this->updateOrderProductPrices($order);

        $this->entity_manager->persist($order);
        $this->entity_manager->flush();

        return $order;
    }

    /** @throws NotFoundException */
    public function getByIdForUser(int $id, User $user): Order
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('id', $id))
            ->andWhere(Criteria::expr()->eq('user', $user))
            ->andWhere(Criteria::expr()->in('status', (new OrderStatusDictionary())->getVisibleIds()))
        ;

        return $this->order_repository->matching($criteria)->first() ?: throw new NotFoundException();
    }

    public function getById(int $id): Order
    {
        return $this->order_repository->find($id) ?? throw new NotFoundHttpException();
    }

    private function updateOrderProductPrices(Order $order): void
    {
        /** @var OrderProduct $order_product */
        foreach ($order->order_products as $order_product) {
            $order_product->price = $order_product->product->price;
        }
    }

    public function updateById(int $id, OrderDTO $dto): void
    {
        $order = $this->getById($id);

        $order->status = $dto->status;
        $order->address = $dto->address;

        foreach ($dto->order_product_dtos as $order_product_dto) {
            $product_id_criteria = Criteria::create()
                ->where(Criteria::expr()->eq('product_id', $order_product_dto->product_id))
            ;

            $order_product = $order->order_products->matching($product_id_criteria)->first();

            if (!$order_product) {
                $order_product = new OrderProduct();
                $order_product->order = $order;
                $order_product->product_id = $order_product_dto->product_id;
            }

            $order_product->price = $this->fromMoneyToDbValue($order_product_dto->price);
            $order_product->amount = $order_product_dto->amount;

            $this->entity_manager->persist($order_product);
        }

        $this->entity_manager->persist($order);
        $this->entity_manager->flush();
    }
}
