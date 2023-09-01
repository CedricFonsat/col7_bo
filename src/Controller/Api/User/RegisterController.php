<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EntityManagerInterface $em,
        private readonly JWTTokenManagerInterface $jwtManager
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $dataUser = json_decode($request->getContent(), true);
        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $dataUser['email']]);

        if ($existingUser) {
            return $this->json([
                'error' => true,
                'code' => 'existing_email'
            ]);
        }

        if (!preg_match('/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*\d)\S*$/', $dataUser['password'])) {
            return $this->json([
                'error' => true,
                'code' => 'password_too_weak'
            ]);
        }

        $user = new User();
        $user->setNickname($dataUser['nickname']);
        $user->setEmail($dataUser['email']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $dataUser['password']));

        $this->em->persist($user);
        $this->em->flush();

        $token = $this->jwtManager->create($user);
        return new JsonResponse(['token' => $token]);

       // return $user;

    }
}