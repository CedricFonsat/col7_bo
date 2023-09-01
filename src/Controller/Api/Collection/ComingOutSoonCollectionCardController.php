<?php

namespace App\Controller\Api\Collection;

use App\Entity\CollectionCard;
use App\Repository\CollectionCardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ComingOutSoonCollectionCardController extends AbstractController
{
    public function __invoke(Request $request, CollectionCardRepository $collectionCardRepository): array
    {
        return [
            'data' => $collectionCardRepository->findBy(['isComingSoon' => true]),
            'cover' => CollectionCard::getCoverToEventComingSoon()
        ];
    }
}