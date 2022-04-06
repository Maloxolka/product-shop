<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Requests\BaseRequest;
use App\DTO\MessageDTO;
use App\Services\MessageService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    #[Route('/contact-us', name: 'app_contact_us', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('contact-us.html.twig');
    }

    #[Route('/contact-us', name: 'app_contact_us_send_message', methods: ['POST'])]
    public function send(BaseRequest $request, MessageService $service): Response
    {
        $dto = new MessageDTO(
            $request->getString('name'),
            $request->getString('contacts'),
            $request->getString('subject'),
            $request->getString('body'),
        );

        $dto->attachment = $request->files->get('file');

        $service->send($dto);

        return $this->redirectToRoute('app_products_index');
    }
}
