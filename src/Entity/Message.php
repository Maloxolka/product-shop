<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string')]
    public string $name;

    #[ORM\Column(type: 'string')]
    public string $contacts;

    #[ORM\Column(type: 'string')]
    public string $subject;

    #[ORM\Column(type: 'string', length: 2048)]
    public string $body;

    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $file_link = null;
}
