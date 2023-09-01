<?php

namespace App\Controller\Api\User;

use App\Entity\Card;
use App\Entity\CardFavoris;
use App\Entity\User;
use App\Repository\CardFavorisRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Util\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AddFavoriteCardController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em){}

  //  #[ParamConverter('card', options: ['mapping' => ['cardId' => 'id']])]
    public function __invoke(User $user, Card $card, CardFavorisRepository $cardFavorisRepository): JsonResponse
    {

       $ifExistCard = $cardFavorisRepository->findOneBy(['user' => $user, 'card' => $card]);

       if ($ifExistCard){
           $this->em->remove($ifExistCard);
       }else{
           $cardFavoris = new CardFavoris();
           $cardFavoris->setCard($card);
           $cardFavoris->setUser($user);
           $this->em->persist($cardFavoris);
       }

        $this->em->flush();

        return $this->json([
            'success' => true
        ]);
    }
}