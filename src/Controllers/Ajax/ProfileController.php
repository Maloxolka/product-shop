<?php

declare(strict_types=1);

namespace App\Controllers\Ajax;

use App\Components\Requests\BaseRequest;
use App\Controllers\AbstractController;
use App\DTO\UserUpdateDTO;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile/ajax', name: 'app_profile_ajax_update', methods: ['POST'])]
    public function update(BaseRequest $request, UserService $service): Response
    {
        $dto = new UserUpdateDTO($request->getString('email'));
        $dto->name = $request->getString('name');
        $dto->surname = $request->getString('surname');

        $service->update($this->getUserOrFail(), $dto);

        return $this->redirectToRoute('app_profile_index');
    }
}