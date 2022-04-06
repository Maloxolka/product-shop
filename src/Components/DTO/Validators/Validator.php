<?php

declare(strict_types=1);

namespace App\Components\DTO\Validators;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    protected function throwExceptionOnViolations(ConstraintViolationListInterface $violations): void
    {
        if ($violations->count() > 0) {
            throw new ValidationFailedException('', $violations);
        }
    }
}
