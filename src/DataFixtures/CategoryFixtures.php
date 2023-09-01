<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

final class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['category'];
    }

    public function load(ObjectManager $manager): void
    {

        $categoryData = [
            ['name' => 'Football'],
            ['name' => 'Metaverse'],
            ['name' => 'Autre'],
        ];

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

}
