<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authentication_utils): Response
    {
        return $this->render('authentication/login.html.twig', [
            'error' => $authentication_utils->getLastAuthenticationError(),
        ]);
    }
}
