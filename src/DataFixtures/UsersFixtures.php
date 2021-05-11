<?php

namespace App\DataFixtures;

use App\Entity\UsersSP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*$faker = Factory::create('fr_FR');

        for($i=0; $i<5; $i++) {

            $user = new UsersSP();
            $user->setPseudo("pseudo_" . $i);
            $user->setNom($faker->name());
            $user->setPrenom($faker->firstName());
            $user->setAdresse($faker->address());
            $user->setCodePostal($faker->postcode());
            $user->setVille($faker->city());
            $user->setTelephone($faker->phoneNumber());
            $user->setPays('FR');
            $user->setEmail($faker->email());
            $user->setPassword("test");
            $user->setRoles(['ROLE_USER']);
            $user->setDateCreation($faker->DateTime('now', 'Europe/Paris'));
            $user->setDateModification($faker->DateTime('now', 'Europe/Paris'));

            $manager->persist($user);
            $manager->flush();
        }*/   
    }
}
