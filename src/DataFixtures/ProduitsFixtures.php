<?php

namespace App\DataFixtures;

use App\Entity\Produits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ProduitsFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRODUIT_REFERENCE = "produit";

    public function getDependencies()
    {
        return [
            CategoriesFixtures::class,
            TauxTvaFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i=0; $i<15; $i++) {

            $produit = new Produits();
            $produit->setUGS($faker->text(5));
            $produit->setNom('produit_' . $i);
            $produit->setEstPublie(true);
            $produit->setMisEnAvant(true);
            $produit->setVisibiliteCatalogue(true);
            $produit->setDescriptionCourte($faker->sentence(6, true));
            $produit->setDescription($faker->sentence(20, true));
            $produit->setDateDebutPromo($faker->DateTime('now', 'Europe/Paris'));
            $produit->setDateFinPromo($faker->DateTime('now', 'Europe/Paris'));
            $produit->setEtatTva(true);
            $produit->setQuantiteStock($faker->randomDigitNotNull);
            $produit->setLimiteBasseStock(1);
            $produit->setCommandeSansStock(false);
            $produit->setVenteIndividuelle(true);
            $produit->setEstEvaluable(true);
            $produit->setTarif($faker->randomFloat(2, 5, 100));
            $produit->setTarifPromo($faker->randomFloat(2, 3, 80));
            $produit->setImage("image");
            $produit->setNombreTelechargements($faker->randomDigitNotNull);
            $produit->setDelaiTelechargement($faker->randomDigitNotNull);
            $produit->setTauxTvaId($this->getReference(TauxTvaFixtures::TAUX_TVA_REFERENCE));
            $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIES_REFERENCE));

            $manager->persist($produit);
            $manager->flush();

            if($i == 14) {

                $this->addReference(self::PRODUIT_REFERENCE, $produit);
            }
        }   
    }
}
