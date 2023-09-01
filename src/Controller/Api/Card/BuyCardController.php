<?php

namespace App\Controller\Api\Card;

use App\Entity\Card;
use App\Repository\CardRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BuyCardController extends AbstractController
{
    public function __construct( private readonly EntityManagerInterface $em ) {}

    public function __invoke(Request $request, Card $card, CardRepository $cardRepository, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //verification data et security a faire

        $userId = $data["userId"] ?: [];
        $user = $userRepository->find($userId);

        $priceCard = $card->getPrice();
        $userWallet = $user->getWallet();

        if ($userWallet >= $priceCard){
            $wallet = $userWallet - $priceCard;
            $card->setUser($user);
            $card->setIfAvailable(false);
            $user->setWallet($wallet);
            $this->em->persist($user);
            $this->em->persist($card);
            $this->em->flush();

            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false]);
    }
}