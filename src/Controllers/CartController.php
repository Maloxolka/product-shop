<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Formatters\OrderFormatter;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_index', methods: ['GET'])]
    public function index(CartService $service, OrderFormatter $formatter): Response
    {
        $cart_order = $service->getCartOrderForUser($this->getUserOrFail());

        return $this->render('cart/index.html.twig', [
            'cart' => $formatter->formatForCart($cart_order),
        ]);
    }

    #[Route('/cart/form-order', name: 'app_cart_form_order', methods: ['GET'])]
    public function formOrder(CartService $service, OrderFormatter $formatter): Response
    {
        $cart_order = $service->getCartOrderForUser($this->getUserOrFail());

        if ($cart_order->isEmpty()) {
            throw new NotFoundHttpException();
        }

        return $this->render('cart/form-order.html.twig', [
            'order' => $formatter->formatForCart($cart_order),
        ]);
    }
}
