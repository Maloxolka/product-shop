<?php

declare(strict_types=1);

namespace App\DTO;

class UserUpdateDTO
{
    public ?string $name = null;
    public ?string $surname = null;

    public function __construct(
        public string $email,
    ) {
    }
}