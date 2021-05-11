<?php

namespace App\DataFixtures;

use App\Entity\Images;
use App\Repository\ImagesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImagesFixtures extends Fixture
{
    public const IMAGE1 = "image1";
    public const IMAGE2 = "image2";
    public const IMAGE3 = "image3";
    public const IMAGE4 = "image4";
    public const IMAGE5 = "image5";
    public const IMAGE6 = "image6";
    public const IMAGE7 = "image7";
    public const IMAGE8 = "image8";
    public const IMAGE9 = "image9";
    public const IMAGE10 = "image10";
    public const IMAGE11 = "image11";
    public const IMAGE12 = "image12";
    public const IMAGE13 = "image13";
    public const IMAGE14 = "image14";
    public const IMAGE15 = "image15";
    public const IMAGE16 = "image16";
    public const IMAGE17 = "image17";
    public const IMAGE18 = "image18";
    public const IMAGE19 = "image19";
    public const IMAGE20 = "image20";
    public const IMAGE21 = "image21";
    public const IMAGE22 = "image22";
    public const IMAGE23 = "image23";
    public const IMAGE24 = "image24";
    public const IMAGE25 = "image25";
    public const IMAGE26 = "image26";
    public const IMAGE27 = "image27";
    public const IMAGE28 = "image28";
    public const IMAGE29 = "image29";
    public const IMAGE30 = "image30";
    public const IMAGE31 = "image31";
    public const IMAGE32 = "image32";
    public const IMAGE33 = "image33";

    public const IMAGES = [
        "image1",
        "image2",
        "image3",
        "image4",
        "image5",
        "image6",
        "image7",
        "image8",
        "image9",
        "image10",
        "image11",
        "image12",
        "image13",
        "image14",
        "image15",
        "image16",
        "image17",
        "image18",
        "image19",
        "image20",
        "image21",
        "image22",
        "image23",
        "image24",
        "image25",
        "image26",
        "image27",
        "image28",
        "image29",
        "image30",
        "image31",
        "image32",
        "image33"
    ];

    public function load(ObjectManager $manager)
    {
        $images_produits = [
            "1_cts.jpg",
            "2_cts.jpg",
            "5_cts.jpg",
            "10_cts.jpg",
            "20_cts.jpg",
            "50_cts.jpg",
            "1_euro.jpg",
            "2_euro.jpg",
            "5_eur.jpg",
            "10_eur.jpg",
            "20_eur.jpg",
            "50_eur.jpg",
            "100_eur.jpg",
            "200_eur.jpg",
            "500_eur.jpg",
    
            "asterix.jpg",
            "bleuet.jpg",
            "cancer_sein.jpg",
            "dday.jpg",
            "drapeau.jpg",
            "federation.jpg",
            "paix.jpg",
            "sida.jpg",
            "uefa.jpg",
            "veil.jpg",
    
            "5_eur_semeuse.png",
            "25_eur_semeuse.jpg",
            "centre.jpg",
            "npdc.jpg",
            "paca.jpg",
            "liberte.jpg",
            "egalite.jpg",
            "500_eur_gold.jpg",
        ];

        for($i=0; $i<33; $i++) {

            $image = new Images();
            $image->setImage($images_produits[$i]);
            $this->addReference(self::IMAGES[$i], $image);
            $manager->persist($image);
            $manager->flush();
        }
    }
}
