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
            ProduitsFixtures::class,
            TypesCaracteristiquesFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        /*$faker = Factory::create('fr_FR');

        for($i=0; $i<15; $i++) {

            $caracteristiques = new Caracteristiques();
            $caracteristiques->setValeur((rand(100, 40000)/100)); //float aleatoire entre 1.00 et 400.00
            $caracteristiques->setProduitsId($this->getReference(ProduitsFixtures::PRODUIT_REFERENCE));
            $caracteristiques->setTypesCaracteristiquesId($this->getReference(TypesCaracteristiquesFixtures::TYPE_CARACTERISTIQUE_REFERENCE));
            $manager->persist($caracteristiques);
            $manager->flush();
        }*/   
    }
}
