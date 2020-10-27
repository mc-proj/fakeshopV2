<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategoriesFixtures extends Fixture implements DependentFixtureInterface
{
    public const CATEGORIES_REFERENCE = "categorie";

    public function getDependencies()
    {
        return [
            SousCategoriesFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i=0; $i<5; $i++) {

            $categorie = new Categories();
            $categorie->setNom($faker->text(10));
            $categorie->setDescription($faker->sentence(10, true));
            $categorie->setImage('image');
            $categorie->addSousCategoriesId($this->getReference(SousCategoriesFixtures::SOUS_CATEGORIES_REFERENCE));

            $manager->persist($categorie);
            $manager->flush();

            if($i == 4) {

                $this->addReference(self::CATEGORIES_REFERENCE, $categorie);
            }
        }   
    }
}
