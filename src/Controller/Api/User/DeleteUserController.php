<?php

declare(strict_types=1);

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Repository\CardRepository;
use App\Repository\ConnexionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteUserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function __invoke(Request $request, User $user, ConnexionRepository $connexionRepository, CardRepository $cardRepository): JsonResponse
    {
        $userConnexion = $connexionRepository->findOneBy(['user' => $user]);
        $userCard = $cardRepository->findBy(['user' => $user]);

        foreach ($userCard as $card){
            $card->setUser(null);
            $card->setIfAvailable(true);
            $this->em->persist($card);
            $this->em->flush();
        }

        $connexionRepository->remove($userConnexion, true);

        $this->em->remove($user);
        $this->em->flush();

        return new JsonResponse(['success' => true]);
    }
}