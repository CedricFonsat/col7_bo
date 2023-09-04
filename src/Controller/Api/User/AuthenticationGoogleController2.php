<?php

namespace App\Controller\Api\User;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationGoogleController2 extends AbstractController
{
    public function __invoke(Request $request): JsonResponse
    {
        $userData = json_decode($request->getContent(), true);

       dd($userData['nickname'], $userData['email'],$userData['googleId'],$userData['googlePicture']);

        return new JsonResponse(['error' => "Une erreur c'est produite lors de l'authentification"]);
    }
}