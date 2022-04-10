<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Components\Requests\BaseRequest;
use App\DTO\ProductDTO;
use App\Exceptions\NotFoundException;
use App\Formatters\ProductFormatter;
use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ProductController extends AbstractController
{
    #[Route('/admin/products', name: 'app_admin_products_index', methods: ['GET'])]
    public function index(ProductService $service, ProductFormatter $formatter): Response
    {
        $products = $service->getForAdmin();

        return $this->render('admin/products/index.html.twig', [
            'products' => $formatter->formatArray($products),
        ]);
    }

    #[Route('/admin/products/{id}/edit', name: 'app_admin_products_edit', methods: ['GET'])]
    public function edit(int $id, ProductService $service, ProductFormatter $formatter): Response
    {
        try {
            $product = $service->getById($id);

            return $this->render('admin/products/edit.html.twig', [
                'product' => $formatter->format($product),
            ]);
        } catch (NotFoundException $e) {
            throw new NotFoundHttpException();
        }
    }

    #[Route('/admin/products/{id}', name: 'app_admin_products_update', methods: ['PUT', 'POST'])]
    public function update(int $id, BaseRequest $request, ProductService $service): Response
    {
        $product_dto = new ProductDTO(
            $request->getString('name'),
            $request->getString('description'),
            $request->getInt('amount'),
            $request->getFloat('price'),
        );

        $product_dto->file = $request->files->get('file');

        try {
            $service->updateById($id, $product_dto);
        } catch (ValidationFailedException) {
            // all the validation rules are present in view, so shouldn't get here.
            return $this->redirectToRoute('app_admin_products_edit', ['id' => $id]);
        } catch (NotFoundException) {
            throw new NotFoundHttpException();
        }

        return $this->redirectToRoute('app_admin_products_index');
    }

    #[Route('/admin/products/create', name: 'app_admin_products_create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('admin/products/create.html.twig');
    }

    #[Route('/admin/products', name: 'app_admin_products_store', methods: ['POST'])]
    public function store(BaseRequest $request, ProductService $service): Response
    {
        $product_dto = new ProductDTO(
            $request->getString('name'),
            $request->getString('description'),
            $request->getInt('amount'),
            $request->getFloat('price'),
        );

        $product_dto->file = $request->files->get('file');

        try {
            $service->store($product_dto);
        } catch (ValidationFailedException $e) {
            return $this->redirectToRoute('app_admin_products_create');
        }

        return $this->redirectToRoute('app_admin_products_index');
    }
}
