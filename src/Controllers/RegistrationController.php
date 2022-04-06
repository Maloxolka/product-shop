<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Components\Requests\BaseRequest;
use App\DTO\UserRegistrationDTO;
use App\Services\RegistrationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('registration/register.html.twig');
    }

    #[Route('/register', name: 'app_register_store', methods: ['POST'])]
    public function store(
        BaseRequest $request,
        RegistrationService $service,
        UserAuthenticatorInterface $user_authenticator,
        FormLoginAuthenticator $form_login_authenticator,
    ): Response {
        $dto = new UserRegistrationDTO(
            $request->getString('email'),
            $request->getString('password'),
        );

        $user = $service->register($dto);
        $user_authenticator->authenticateUser($user, $form_login_authenticator, $request);

        return $this->redirectToRoute('app_products_index');
    }
}
