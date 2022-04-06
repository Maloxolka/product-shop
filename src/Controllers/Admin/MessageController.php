<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\AbstractController;
use App\Formatters\MessageFormatter;
use App\Services\MessageService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/admin/messages', name: 'app_admin_messages_index', methods: ['GET'])]
    public function index(MessageService $service, MessageFormatter $formatter): Response
    {
        $messages = $service->getAll();

        return $this->render('admin/messages/index.html.twig', [
            'messages' => $formatter->formatArray($messages),
        ]);
    }

    #[Route('/admin/messages/{id}', name: 'app_admin_messages_show', methods: ['GET'])]
    public function show(int $id, MessageService $service, MessageFormatter $formatter): Response
    {
        $message = $service->getById($id);

        return $this->render('admin/messages/show.html.twig', [
            'message' => $formatter->format($message),
        ]);
    }
}
