<?php

declare(strict_types=1);

namespace App\DTO\Validators;

use App\Components\DTO\Validators\Validator;
use App\DTO\UserRegistrationDTO;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Regex;

class UserRegistrationDTOValidator extends Validator
{
    public function validate(UserRegistrationDTO $dto): void
    {
        $violations = $this->validator->startContext()
            ->atPath('email')->validate($dto->email, new Email())
            ->atPath('password')->validate(
                $dto->password,
                new Regex(
                    "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",
                    message: 'Password should be at least 8 symbols long and contain at least one uppercase, '
                        .'one lowercase letter and one number',
                ),
            )
            ->getViolations();

        $this->throwExceptionOnViolations($violations);
    }
}
