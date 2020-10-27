<?php

namespace App\DataFixtures;

use App\Entity\TauxTva;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TauxTvaFixtures extends Fixture
{
    public const TAUX_TVA_REFERENCE = "tva";

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i=0; $i<5; $i++) {

            $tva = new TauxTva();
            $tva->setIntitule("taux_tva_" . $i);
            $tva->setTaux($faker->randomFloat(2, 5, 20));

            $manager->persist($tva);
            $manager->flush();

            if($i == 4) {

                $this->addReference(self::TAUX_TVA_REFERENCE, $tva);
            }
        }   
    }
}
