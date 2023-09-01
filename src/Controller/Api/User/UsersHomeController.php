<?php

namespace App\Controller\Api\User;

use App\Entity\CollectionCard;
use App\Entity\User;
use App\Repository\CardFavorisRepository;
use App\Repository\CardRepository;
use App\Repository\CategoryRepository;
use App\Repository\CollectionCardRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersHomeController extends AbstractController
{
    public function __invoke(
        Request                  $request,
        User $user,
        UserRepository           $userRepository,
        CollectionCardRepository $collectionCardRepository,
        CardFavorisRepository    $cardFavorisRepository,
        CategoryRepository       $categoryRepository): array
    {

        if ($user != null) {
            if ($cardFavorisRepository->findBy(['user' => $userRepository->find(12)])) {
                $userCardsFavoris = $cardFavorisRepository->findBy(['user' => $userRepository->find(12)])[0]->getCards();

                // $userCardsFavoris =  $cardFavorisRepository->findBy(['user' => $user])[0]->getCards();

                $categoryNameCounts = [];

                foreach ($userCardsFavoris as $value) {
                    $categoryName = $value->getCollection()->getCategory()->getName();
                    if (!isset($categoryNameCounts[$categoryName])) {
                        $categoryNameCounts[$categoryName] = 1;
                    } else {
                        $categoryNameCounts[$categoryName]++;
                    }
                }

                $maxCategoryName = array_search(max($categoryNameCounts), $categoryNameCounts);
                $category = $categoryRepository->findBy(['name' => $maxCategoryName]);

                $cover = '';
                if ($maxCategoryName == 'Football') {
                    $cover = CollectionCard::CATEGORY_FOOTBALL;
                }
                if ($maxCategoryName == 'Metaverse') {
                    $cover = CollectionCard::CATEGORY_METAVERSE;
                }
                if ($maxCategoryName == 'Autre') {
                    $cover = CollectionCard::CATEGORY_AUTRE;
                }

                $collectionBestForUser = $collectionCardRepository->findBy(['category' => $category]);

            } else {
                $collectionBestForUser = $collectionCardRepository->findAll();
                $cover = CollectionCard::CATEGORY_AUTRE;
            }

            $users = $userRepository->findUsersWithHighestTotalPrice();
        }

        return [
            'collection' => $collectionBestForUser ?? null,
            'cover' => $cover ?? null,
            'users' => $users ?? null
        ];
    }


}
