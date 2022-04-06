<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Requests\BaseRequest;
use App\Exceptions\NotFoundException;
use App\Formatters\OrderFormatter;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_orders_index', methods: ['GET'])]
    public function index(OrderService $service, OrderFormatter $formatter): Response
    {
        $orders = $service->getUserList($this->getUserOrFail());

        return $this->render('orders/index.html.twig', [
            'orders' => $formatter->formatArray($orders),
        ]);
    }

    #[Route('/orders/{id}', name: 'app_orders_show', methods: ['GET'])]
    public function show(int $id, OrderService $service, OrderFormatter $formatter): Response
    {
        try {
            $order = $service->getByIdForUser($id, $this->getUserOrFail());

            return $this->render('orders/show.html.twig', [
                'order' => $formatter->format($order),
            ]);
        } catch (NotFoundException) {
            throw new NotFoundHttpException();
        }
    }

    #[Route('/orders/form', name: 'app_orders_form', methods: ['POST'])]
    public function form(BaseRequest $request, OrderService $service): Response
    {
        $order = $service->formUserOrder($this->getUserOrFail(), $request->getString('address'));

        return $this->redirectToRoute('app_orders_show', ['id' => $order->id]);
    }
}
