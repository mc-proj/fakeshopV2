<?php

namespace App\DataFixtures;

use App\Entity\TypesCaracteristiques;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypesCaracteristiquesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0; $i<5; $i++) {

            $typeCaracteristique = new TypesCaracteristiques();
            $typeCaracteristique->setNom("categorie_" . $i);

            $manager->persist($typeCaracteristique);
            $manager->flush();
        }   
    }
}
