<?php

namespace App\DataFixtures;

use App\Entity\SousCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SousCategoriesFixtures extends Fixture
{
    public const SOUS_CATEGORIES_REFERENCE = "sous_categorie";

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i=0; $i<5; $i++) {

            $sous_categorie = new SousCategories();
            $sous_categorie->setNom("sous_categorie_" . $i);

            $manager->persist($sous_categorie);
            $manager->flush();

            if($i == 4) {

                $this->addReference(self::SOUS_CATEGORIES_REFERENCE, $sous_categorie);
            }
        }   
    }
}
