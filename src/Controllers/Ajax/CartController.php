<?php

declare(strict_types=1);

namespace App\Controllers\Ajax;

use App\Controllers\AbstractController;
use App\Exceptions\NotFoundException;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\InvalidArgumentException;

class CartController extends AbstractController
{
    #[Route('/cart/add/{product_id}', name: 'app_cart_ajax_add', methods: ['POST'])]
    public function add(int $product_id, CartService $service): Response
    {
        try {
            $service->addProductWithIdToUserCart($product_id, $this->getUserOrFail());

            return $this->jsonSuccess('Item has been successfully added to cart!');
        } catch (InvalidArgumentException $e) {
            return $this->jsonError($e->getMessage());
        } catch (NotFoundException) {
            throw new NotFoundHttpException();
        }
    }

    #[Route('/cart/subtract/{product_id}', name: 'app_cart_ajax_subtract', methods: ['POST'])]
    public function subtract(int $product_id, CartService $service): Response
    {
        try {
            $service->subtractProductWithIdFromUserCart($product_id, $this->getUserOrFail());

            return $this->jsonSuccess('Item has been successfully subtracted from the cart!');
        } catch (InvalidArgumentException $e) {
            return $this->jsonError($e->getMessage());
        } catch (NotFoundException) {
            throw new NotFoundHttpException();
        }
    }
}
