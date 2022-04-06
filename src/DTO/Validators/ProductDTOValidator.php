<?php

declare(strict_types=1);

namespace App\DTO\Validators;

use App\Components\DTO\Validators\Validator;
use App\DTO\ProductDTO;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;

class ProductDTOValidator extends Validator
{
    private function setDefaultValidators(ProductDTO $dto): ContextualValidatorInterface
    {
        return $this->validator->startContext()
            ->atPath('name')->validate($dto->name, new Length(max: 255))
            ->atPath('amount')->validate($dto->amount, new PositiveOrZero())
            ->atPath('price')->validate($dto->price_float, new Positive())
            ->atPath('file')->validate(
                $dto->file,
                new File(mimeTypes: ['image/jpeg', 'image/pjpeg', 'image/png']),
            );
    }

    public function validateForStore(ProductDTO $dto): void
    {
        $violations = $this->setDefaultValidators($dto)
            ->atPath('file')->validate($dto->file, new NotNull())
            ->getViolations();

        $this->throwExceptionOnViolations($violations);
    }

    public function validateForUpdate(ProductDTO $dto): void
    {
        $violations = $this->setDefaultValidators($dto)
            ->getViolations();

        $this->throwExceptionOnViolations($violations);
    }
}
