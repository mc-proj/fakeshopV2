<?php

namespace App\DataFixtures;

use App\Entity\TypesCaracteristiques;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypesCaracteristiquesFixtures extends Fixture
{
    public const TYPE_CARACTERISTIQUE_REFERENCE = "caracteristique_reference";

    public function load(ObjectManager $manager)
    {
        /*for($i=0; $i<5; $i++) {

            $typeCaracteristique = new TypesCaracteristiques();
            $typeCaracteristique->setNom("caracteristique_" . $i);
            $manager->persist($typeCaracteristique);
            $this->addReference(self::TYPE_CARACTERISTIQUE_REFERENCE, $typeCaracteristique);
            $manager->flush();
        }*/   
    }
}
