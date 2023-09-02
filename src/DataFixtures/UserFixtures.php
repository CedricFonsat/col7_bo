<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\CollectionCard;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher){}

    public static function getGroups(): array
    {
        return ['user'];
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setEmail('hello@collect7.fr');
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                'AZerty123@'
            )
        );
        $user->setNickname('hello');
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        for ($i = 0; $i < 10; $i++){
            $faker = Faker\Factory::create();

            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'AZerty123@'
                )
            );
            $user->setNickname($faker->lastName);
            $manager->persist($user);
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
