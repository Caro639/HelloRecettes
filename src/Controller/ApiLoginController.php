<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class ApiLoginController extends AbstractController
{
    // #[Route('/api/login', name: 'app_api_login', methods: 'GET', 'POST')]
    // public function login(AuthenticationUtils $authenticationUtils, User $user): Response
    // {
    //     if (null === $user) {
    //         return $this->json([
    //             'message' => 'missing credentials',
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }

    //     $token = 'token'; // somehow create an API token for $user

    //     return $this->json([
    //         'user' => $user->getUserIdentifier(),
    //         'token' => $token,
    //     ]);
    // }

    #[Route('/api/logout', 'app_api_login.logout')]
    public function logout()
    {
        // Nothing to do here..
    }
}