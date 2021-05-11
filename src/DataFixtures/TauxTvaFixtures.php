<?php

namespace App\DataFixtures;

use App\Entity\TauxTva;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TauxTvaFixtures extends Fixture
{
    public const TAUX_TVA_STD = "tva_std";
    public const TAUX_TVA_BAS = "tva_bas";

    public function load(ObjectManager $manager)
    {
        $tva_standard = new TauxTva();
        $tva_standard->setIntitule("taux_tva_standard");
        $tva_standard->setTaux(1950);
        $manager->persist($tva_standard);
        $this->addReference(self::TAUX_TVA_STD, $tva_standard);

        $tva_bas = new TauxTva();
        $tva_bas->setIntitule("taux_tva_bas");
        $tva_bas->setTaux(550);
        $manager->persist($tva_bas);
        $this->addReference(self::TAUX_TVA_BAS, $tva_bas);

        $manager->flush(); 
    }
}
