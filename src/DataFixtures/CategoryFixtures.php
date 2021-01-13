<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    const CATEGORIES = [
        'feverol', 'tournesol', 'ble', 'avoine', 'triticale', 'orge', 'mais', 'pois', 'colza'
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('category_' . $categoryName, $category);
            $manager->persist($category);
        }
            $manager->flush();
    }
}
