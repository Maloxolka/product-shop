<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\MessageDTO;
use App\Entity\Message;
use App\Exceptions\NotFoundException;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    public function __construct(
        private EntityManagerInterface $entity_manager,
        private MediaService $media_service,
        private MessageRepository $message_repository,
    ) {
    }

    /**
     * @return Message[]
     */
    public function getAll(): array
    {
        return $this->message_repository->findAll();
    }

    /**
     * @throws NotFoundException
     */
    public function getById(int $id): Message
    {
        return $this->message_repository->find($id) ?? throw new NotFoundException();
    }

    public function send(MessageDTO $dto): void
    {
        $message = new Message();
        $message->name = $dto->name;
        $message->contacts = $dto->contacts;
        $message->subject = $dto->subject;
        $message->body = $dto->body;

        if (!is_null($dto->attachment)) {
            $file_name = uniqid().'.'.$dto->attachment->getClientOriginalExtension();

            $message->file_link = $this->media_service
                ->putFileToUploadsFolderAs($dto->attachment, 'messages/'.$file_name);
        }

        $this->entity_manager->persist($message);
        $this->entity_manager->flush();
    }
}
