<?php

namespace App\DataFixtures;

use App\Entity\Caracteristiques;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CaracteristiquesFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            ProduitsFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i=0; $i<15; $i++) {

            $caracteristiques = new Caracteristiques();
            $caracteristiques->setValeur($faker->randomFloat(2, 1, 400)); //$nbMaxDecimals, $min, $max
            $caracteristiques->setProduitsId($this->getReference(ProduitsFixtures::PRODUIT_REFERENCE));

            $manager->persist($caracteristiques);
            $manager->flush();
        }   
    }
}
