<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CollectionCard;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

final class CollectionCardFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['collection_card'];
    }

    public function load(ObjectManager $manager): void
    {

        $collectionCard = new CollectionCard();
        $collectionCard->setName();
        $collectionCard->setCategory();
        $collectionCard->setAuthor();
        $collectionCard->setIsComingSoon();


        $collectionData = [
            [

            ]
        ]
        $category = $manager->getRepository(Category::class)->find(rand(7, 9));

        $i = 0;
        foreach ($categoryData as $data) {
            $i += 1;
            $category = new Category();
            $category->setName($data['name']);
            $manager->persist($category);
            $manager->flush();
        }

        $manager->flush();
        return;
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class
        ];
    }

}
