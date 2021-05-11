<?php

namespace App\DataFixtures;

use App\Entity\Produits;
use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ProduitsFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRODUIT_REFERENCE1 = "produit1";
    public const PRODUIT_REFERENCE2 = "produit2";
    public const PRODUIT_REFERENCE3 = "produit3";
    public const PRODUIT_REFERENCE4 = "produit4";
    public const PRODUIT_REFERENCE5 = "produit5";
    public const PRODUIT_REFERENCE6 = "produit6";
    public const PRODUIT_REFERENCE7 = "produit7";
    public const PRODUIT_REFERENCE8 = "produit8";
    public const PRODUIT_REFERENCE9 = "produit9";
    public const PRODUIT_REFERENCE10 = "produit10";
    public const PRODUIT_REFERENCE11 = "produit11";
    public const PRODUIT_REFERENCE12 = "produit12";
    public const PRODUIT_REFERENCE13 = "produit13";
    public const PRODUIT_REFERENCE14 = "produit14";
    public const PRODUIT_REFERENCE15 = "produit15";
    public const PRODUIT_REFERENCE16 = "produit16";
    public const PRODUIT_REFERENCE17 = "produit17";
    public const PRODUIT_REFERENCE18 = "produit18";
    public const PRODUIT_REFERENCE19 = "produit19";
    public const PRODUIT_REFERENCE20 = "produit20";
    public const PRODUIT_REFERENCE21 = "produit21";
    public const PRODUIT_REFERENCE22 = "produit22";
    public const PRODUIT_REFERENCE23 = "produit23";
    public const PRODUIT_REFERENCE24 = "produit24";
    public const PRODUIT_REFERENCE25 = "produit25";
    public const PRODUIT_REFERENCE26 = "produit26";
    public const PRODUIT_REFERENCE27 = "produit27";
    public const PRODUIT_REFERENCE28 = "produit28";
    public const PRODUIT_REFERENCE29 = "produit29";
    public const PRODUIT_REFERENCE30 = "produit30";
    public const PRODUIT_REFERENCE31 = "produit31";
    public const PRODUIT_REFERENCE32 = "produit32";
    public const PRODUIT_REFERENCE33 = "produit33";

    public const PRODUITS = [
        "produit1",
        "produit2",
        "produit3",
        "produit4",
        "produit5",
        "produit6",
        "produit7",
        "produit8",
        "produit9",
        "produit10",
        "produit11",
        "produit12",
        "produit13",
        "produit14",
        "produit15",
        "produit16",
        "produit17",
        "produit18",
        "produit19",
        "produit20",
        "produit21",
        "produit22",
        "produit23",
        "produit24",
        "produit25",
        "produit26",
        "produit27",
        "produit28",
        "produit29",
        "produit30",
        "produit31",
        "produit32",
        "produit33"
    ];

    public function getDependencies()
    {
        return [
            CategoriesFixtures::class,
            TauxTvaFixtures::class,
            ImagesFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $noms = [
            "piece commune 1 centime",
            "piece commune 2 centimes",
            "piece commune 5 centimes",
            "piece commune 10 centimes",
            "piece commune 20 centimes",
            "piece commune 50 centimes",
            "piece commune 1 euro",
            "piece commune 2 euros",
            "billet commun 5 euros",
            "billet commun 10 euros",
            "billet commun 20 euros",
            "billet commun 50 euros",
            "billet commun 100 euros",
            "billet commun 200 euros",
            "billet commun 500 euros",

            "piece 2 euros asterix",
            "piece 2 euros bleuet",
            "piece 2 euros cancer du sein",
            "piece 2 euros dday",
            "piece 2 euros drapeau",
            "piece 2 euros federation",
            "piece 2 euros paix",
            "piece 2 euros sida",
            "piece 2 euros uefa",
            "piece 2 euros veil",

            "piece collection 5 euros semeuse",
            "piece collection 25 euros semeuse",
            "piece collection region centre",
            "piece collection region ndpc",
            "piece collection region paca",
            "piece collection 5 euros liberte",
            "piece collection 5 euros egalite",
            "piece collection 500 euros"
        ];

        $descriptions_courtes = [

            "piece commune de 1 centime",
            "piece commune de 2 centimes",
            "piece commune de 5 centimes",
            "piece commune de 10 centimes",
            "piece commune de 20 centimes",
            "piece commune de 50 centimes",
            "piece commune de 1 euro",
            "piece commune de 2 euros",
            "billet commun de 5 euros",
            "billet commun de 10 euros",
            "billet commun de 20 euros",
            "billet commun de 50 euros",
            "billet commun de 100 euros",
            "billet commun de 200 euros",
            "billet commun de 500 euros",

            "piece commémorative asterix",
            "piece commémorative bleuet",
            "piece commémorative cancer du sein",
            "piece commémorative dday",
            "piece commémorative drapeau",
            "piece commémorative federation",
            "piece commémorative paix",
            "piece commémorative sida",
            "piece commémorative uefa",
            "piece commémorative veil",

            "piece de collection 5€ semeuse",
            "piece de collection 25€ semeuse",
            "piece de collection 10€ centre",
            "piece de collection 10€ ndpc",
            "piece de collection 10€ paca",
            "piece de collection 5€ liberte",
            "piece de collection 5€ egalite",
            "piece de collection 500€ or"
        ];

        $descriptions_longues = [

            "piece commune de 1 centime émise en 2002",
            "piece commune de 2 centimes émise en 2002",
            "piece commune de 5 centimes émise en 2002",
            "piece commune de 10 centimes émise en 2002",
            "piece commune de 20 centimes émise en 2002",
            "piece commune de 50 centimes émise en 2002",
            "piece commune de 1 euro émise en 2002",
            "piece commune de 2 euros émise en 2002",
            "billet commun de 5 euros émis en 2013",
            "billet commun de 10 euros émis en 2014",
            "billet commun de 20 euros émis en 2015",
            "billet commun de 50 euros émis en 2017",
            "billet commun de 100 euros émis en 2002",
            "billet commun de 200 euros émis en 2002",
            "billet commun de 500 euros émis en 2002",

            "piece emise pour les 60 ans d'asterix",
            "commemore le bleuet, fleur de mémoire envers nos anciens combattant",
            "commemore la recherche sur le cancer du sein",
            "commemore des 70 ans du débarquement en Normandie",
            "commemore les 30 ans du drapeau Européen ",
            "commemore la République",
            "commemore l'une des plus longues periodes de paix en Europe",
            "commémore la journée mondiale de lutte contre le SIDA",
            "commemore la compétion de football de l'Euro 2016 ",
            "commemore Simone VEIL",

            "La pièce de 5 € Semeuse en Marche en Argent 500 de 2008",
            "La pièce de 25 € Semeuse en Marche en Argent 900 de 2009",
            "La pièce de 10 € en Argent 900 de 2010 de la région Centre",
            "La pièce de 10 € en Argent 900 de 2010 de la région Nord Pas De Calais",
            "La pièce de 10 € en Argent 900 de 2010 de la région Provence Alpes Cote d'Azur",
            "La pièce Liberté de 5€ en Argent 333/1000 2013 de la série des Valeurs de la République",
            "La pièce Egalité de 5€ en Argent 333/1000 2013 de la série des Valeurs de la République",
            "La pièce de 500€ Semeuse en Marche en OR 999/1000 de 2010"
        ];

        $prix = [

            1,
            2,
            5,
            10,
            20,
            50,
            100,
            200,
            500,
            1000,
            2000,
            5000,
            10000,
            20000,
            50000,

            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,
            200,

            500,
            2500,
            1000,
            1000,
            1000,
            500,
            500,
            50000
        ];

        for($i=0; $i < 33; $i++) {

            $produit = new Produits();
            $produit->setUGS("ref_".$i);
            $produit->setNom($noms[$i]);
            $produit->setEstPublie(true);

            if($i < 30 && ($i%5 == 0)) {

                $produit->setMisEnAvant(true);
            } else {
                $produit->setMisEnAvant(false);
            }

            $produit->setVisibiliteCatalogue(true);
            $produit->setDescriptionCourte($descriptions_courtes[$i]);
            $produit->setDescription($descriptions_longues[$i]);
            //!\ possibilite de date de fin anterieure a la date de debut --> laisse tel quel pour simple test
            $produit->setDateDebutPromo($faker->DateTime('now', 'Europe/Paris'));
            $produit->setDateFinPromo($faker->DateTime('now', 'Europe/Paris'));
            $produit->setEtatTva(true);
            $produit->setQuantiteStock(rand(2, 99));
            $produit->setLimiteBasseStock(1);
            $produit->setCommandeSansStock(false);
            $produit->setVenteIndividuelle(true);
            $produit->setEstEvaluable(true);
            $produit->setTarif($prix[$i]);
            $produit->setTarifPromo($prix[$i] - 1);
            $produit->setNombreTelechargements(rand(1, 20));
            $produit->setDelaiTelechargement(rand(10, 30));
            $produit->setDateCreation($faker->DateTime('now', 'Europe/Paris'));

            if($i%2 == 0) {

                $produit->setTauxTvaId($this->getReference(TauxTvaFixtures::TAUX_TVA_STD));
            } else {

                $produit->setTauxTvaId($this->getReference(TauxTvaFixtures::TAUX_TVA_BAS));
            }

            if($i < 15) {

                $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_COMMUNES));

                if($i > 7) {

                    $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_BILLETS));
                } else {

                    $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_PIECES));
                }
            } else if($i < 25) {

                $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_COMMEMORATIVES));
                $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_PIECES));
            } else {

                $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_COLLECTION));
                $produit->addCategories($this->getReference(CategoriesFixtures::CATEGORIE_PIECES));
            }

            $produit->addImage($this->getReference(ImagesFixtures::IMAGES[$i]));
            $manager->persist($produit);
            $manager->flush();

        } 
    }
}
