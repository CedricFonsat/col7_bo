<?php

namespace App\Controller\Api\Card;

use App\Entity\Card;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchCardController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }
    public function __invoke(Request $request, CardRepository $cardRepository): array
    {

      //  dd($this->getUser());


        $data = json_decode($request->getContent(), true);



      // dd($cardRepository->searchCard($data['query']));

        return $cardRepository->searchCard($data['query']);
    }
}