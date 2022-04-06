<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Formatters\ProductFormatter;
use App\Services\ProductService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/', name: 'app_products_index', methods: ['GET'])]
    public function index(ProductService $service, ProductFormatter $formatter): Response
    {
        $products = $service->getForIndexPage();

        return $this->render('index.html.twig', [
            'products' => $formatter->formatArray($products),
        ]);
    }
}
