<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoriesFixtures extends Fixture
{
    public const CATEGORIE_PIECES = "categorie_pieces";
    public const CATEGORIE_BILLETS = "categorie_billets";
    public const CATEGORIE_COLLECTION = "categorie_collection";
    public const CATEGORIE_COMMEMORATIVES = "categorie_commemoratives";
    public const CATEGORIE_COMMUNES = "categorie_communes";
    private $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository) {

        $this->categoriesRepository = $categoriesRepository;
    }

    public function load(ObjectManager $manager)
    {
        $pieces = new Categories();
        $pieces->setNom("pièces");
        $pieces->setDescription("pièces");
        $pieces->setImage("pieces.jpg");

        $billets = new Categories();
        $billets->setNom("billets");
        $billets->setDescription("billets");
        $billets->setImage("billets.jpg");

        $collection = new Categories();
        $collection->setNom("collection");
        $collection->setDescription("collection");
        $collection->setImage("image.jpg");

        $commemoratives = new Categories();
        $commemoratives->setNom("commemoratives");
        $commemoratives->setDescription("commemoratives");
        $commemoratives->setImage("commemoratives.jpg");

        $communes = new Categories();
        $communes->setNom("communes");
        $communes->setDescription("communes");
        $communes->setImage("communes.jpg");

        $pieces->addCategory($collection);
        $pieces->addCategory($commemoratives);
        $pieces->addCategory($communes);
        $billets->addCategory($communes);

        $this->addReference(self::CATEGORIE_PIECES, $pieces);
        $this->addReference(self::CATEGORIE_BILLETS, $billets);
        $this->addReference(self::CATEGORIE_COLLECTION, $collection);
        $this->addReference(self::CATEGORIE_COMMEMORATIVES, $commemoratives);
        $this->addReference(self::CATEGORIE_COMMUNES, $communes);

        $manager->persist($communes);
        $manager->persist($commemoratives);
        $manager->persist($collection);
        $manager->persist($billets);
        $manager->persist($pieces);
        $manager->flush(); 
    }
}
