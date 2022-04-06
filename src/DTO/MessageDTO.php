<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MessageDTO
{
    public ?UploadedFile $attachment = null;

    public function __construct(
        public string $name,
        public string $contacts,
        public string $subject,
        public string $body,
    ) {
    }
}
