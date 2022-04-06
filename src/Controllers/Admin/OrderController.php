<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Components\Requests\BaseRequest;
use App\Controllers\AbstractController;
use App\Dictionaries\OrderStatuses\OrderStatusDictionary;
use App\DTO\Factories\OrderDTOFactory;
use App\Formatters\Dictionaries\OrderStatusFormatter;
use App\Formatters\OrderFormatter;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/admin/orders', name: 'app_admin_orders_index', methods: ['GET'])]
    public function index(OrderService $service, OrderFormatter $formatter): Response
    {
        $orders = $service->getAdminList();

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $formatter->formatArray($orders),
        ]);
    }

    #[Route('/admin/orders/{id}/edit', name: 'app_admin_orders_edit', methods: ['GET'])]
    public function edit(
        int $id,
        OrderService $service,
        OrderFormatter $formatter,
        OrderStatusDictionary $order_status_dictionary,
        OrderStatusFormatter $order_status_formatter,
    ): Response {
        $order = $service->getById($id);

        return $this->render('admin/orders/edit.html.twig', [
            'order' => $formatter->format($order),
            'dictionaries' => [
                'order_statuses' => $order_status_formatter->formatArray($order_status_dictionary->all()),
            ],
        ]);
    }

    #[Route('/admin/orders/{id}', name: 'app_admin_orders_update', methods: ['POST', 'PUT'])]
    public function update(int $id, BaseRequest $request, OrderService $service, OrderDTOFactory $dto_factory): Response
    {
        $dto = $dto_factory->fromRequest($request);

        $service->updateById($id, $dto);

        return $this->redirectToRoute('app_admin_products_index');
    }
}
