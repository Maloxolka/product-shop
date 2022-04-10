<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\UserUpdateDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entity_manager,
    )
    {
    }

    public function update(User $user, UserUpdateDTO $dto): void
    {
        $user->name = $dto->name;
        $user->surname = $dto->surname;
        $user->email = $dto->email;

        $this->entity_manager->persist($user);
        $this->entity_manager->flush();
    }
}