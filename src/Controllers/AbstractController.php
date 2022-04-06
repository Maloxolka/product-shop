<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function getUserOrFail(): User
    {
        $user = $this->getUser();

        if (is_null($user)) {
            throw new AccessDeniedException();
        }
        /* @var User $user */
        return $user;
    }

    public function jsonSuccess(string $message): JsonResponse
    {
        return $this->json([
            'code' => 200,
            'message' => $message,
        ]);
    }

    public function jsonError(string $message): JsonResponse
    {
        return $this->json([
            'code' => 422,
            'message' => $message,
        ]);
    }
}
