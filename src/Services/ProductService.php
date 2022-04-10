<?php

declare(strict_types=1);

namespace App\Services;

use App\Components\Traits\ConvertPriceTrait;
use App\DTO\ProductDTO;
use App\DTO\Validators\ProductDTOValidator;
use App\Entity\Product;
use App\Exceptions\NotFoundException;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductService
{
    use ConvertPriceTrait;

    public function __construct(
        private ProductRepository $repository,
        private EntityManagerInterface $entity_manager,
        private ProductDTOValidator $validator,
        private MediaService $media_service,
        private OrderRepository $order_repository,
    ) {
    }

    /**
     * @return Product[]
     */
    public function getForAdmin(): array
    {
        return $this->repository->findAll();
    }

    public function getForIndexPage(): array
    {
        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->gt('amount', 0));

        return $this->repository->matching($criteria)->getValues();
    }

    /** @throws NotFoundException */
    public function getById(int $id): Product
    {
        return $this->repository->find($id) ?? throw new NotFoundException();
    }

    /** @throws NotFoundException */
    public function updateById(int $id, ProductDTO $dto): void
    {
        $this->validator->validateForUpdate($dto);

        $product = $this->getById($id);

        $this->fillEntity($product, $dto);

        if (!is_null($dto->file)) {
            $this->media_service->deleteFromUploadsFolder($product->file_link);

            $product->file_link = $this->generateLinkForFile($dto->file);
        }

        $this->entity_manager->persist($product);
        $this->entity_manager->flush();
    }

    public function store(ProductDTO $dto): void
    {
        $this->validator->validateForStore($dto);
        $product = new Product();

        $this->fillEntity($product, $dto);

        $product->file_link = $this->generateLinkForFile($dto->file);

        $this->entity_manager->persist($product);
        $this->entity_manager->flush();
    }

    private function generateLinkForFile(UploadedFile $file): string
    {
        $file_name = uniqid().'.'.$file->getClientOriginalExtension();

        return $this->media_service->putFileToUploadsFolderAs($file, 'products/'.$file_name);
    }

    private function fillEntity(Product $entity, ProductDTO $dto): void
    {
        $entity->name = $dto->name;
        $entity->price = $this->fromMoneyToDbValue($dto->price_float);
        $entity->amount = $dto->amount;
        $entity->description = $dto->description;
    }
}
