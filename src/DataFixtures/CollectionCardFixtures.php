<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\CollectionCard;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker;

final class CollectionCardFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['collection_card'];
    }

    public function load(ObjectManager $manager): void
    {


        for ($i = 0; $i < 10; $i++){
            $faker = Faker\Factory::create();
            $category = $manager->getRepository(Category::class)->findAll();
            $collectionCard = new CollectionCard();
            $collectionCard->setName($faker->name);
            $collectionCard->setDescription($faker->paragraph);
            $collectionCard->setCategory($category[rand(1, 2)]);
            $collectionCard->setAuthor('collect7');
            $collectionCard->setIsComingSoon($faker->boolean);
            $collectionCard->setImageName($faker->imageUrl());
            $manager->persist($collectionCard);
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
