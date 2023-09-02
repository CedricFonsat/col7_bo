<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\CollectionCard;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker;

final class CardFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['card'];
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 100; $i++){
            $faker = Faker\Factory::create();
            $collectionCard = $manager->getRepository(CollectionCard::class)->findAll();
            $card = new Card();
            $card->setName($faker->name);
            $card->setCollection($collectionCard[rand(1, 9)]);
            $card->setImageName($faker->imageUrl);
            $card->setIfAvailable($faker->boolean);
            $card->setPrice(200);
            $manager->persist($card);
        }

        $manager->flush();
        return;
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            CollectionCardFixtures::class
        ];
    }

}
