<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\UserRegistrationDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationService
{
    public function __construct(
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $user_password_hasher,
        private EntityManagerInterface $entity_manager,
    ) {
    }

    public function register(UserRegistrationDTO $dto): User
    {
        $this->validator->validate($dto);

        $user = new User();

        $user->setEmail($dto->email);

        $user->setPassword(
            $this->user_password_hasher->hashPassword(
                $user,
                $dto->password
            )
        );

        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        return $user;
    }
}
