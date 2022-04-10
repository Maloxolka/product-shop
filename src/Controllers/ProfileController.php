<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Requests\BaseRequest;
use App\DTO\UserUpdateDTO;
use App\Formatters\UserFormatter;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_index', methods: ['GET'])]
    public function index(UserFormatter $formatter): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $formatter->formatOne($this->getUserOrFail()),
        ]);
    }
}