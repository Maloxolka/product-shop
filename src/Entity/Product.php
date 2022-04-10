<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 255)]
    public string $name;

    #[ORM\Column(type: 'string', length: 1024, nullable: true)]
    public ?string $description;

    #[ORM\Column(type: 'integer')]
    public int $price;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    public int $amount;

    #[ORM\Column(type: 'string', length: 255)]
    public string $file_link;
}
