<?php

namespace App\Repository;

use App\Entity\Produits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Produits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produits[]    findAll()
 * @method Produits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produits::class);
    }

    public function findProduit($debut) {

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('c.category', 'cc') 
            ->andWhere('cc.nom IS NOT NULL')
            ->leftJoin('p.categories', 'sc')
            ->andWhere('p.nom LIKE :debut')
            ->setParameter('debut', '%'.$debut.'%')
            ->select('p.nom AS nom_produit, sc.nom AS nom_sous_categorie, c.nom AS nom_categorie')
            ->groupBy('nom_produit')
            ->orderBy('nom_produit', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function rechercheGenerale($recherche) {

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('c.sous_categories', 'cc')
            ->andWhere('p.nom LIKE :recherche
                        OR p.description LIKE :recherche
                        OR p.description_courte LIKE :recherche
                        OR c.nom LIKE :recherche
                        OR c.description LIKE :recherche
                        OR cc.nom LIKE :recherche
                        OR cc.description LIKE :recherche
                        ')
            ->setParameter('recherche', '%'.$recherche.'%')
            ->getQuery()
            ->getResult();
    }

    public function getBest($quantite) {

        return $this->createQueryBuilder('p')
            ->where('p.mis_en_avant = 1')
            ->andWhere('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->setMaxResults($quantite)
            ->getQuery()
            ->getResult();
    }

    public function getLatest($quantite) {

        return $this->createQueryBuilder('p')
            ->where('p.mis_en_avant = 1')
            ->andWhere('p.est_publie = 1')//
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->orderBy('p.date_creation', 'DESC')
            ->setMaxResults($quantite)
            ->getQuery()
            ->getResult();
    }

    public function getByCategorie($categorie, $quantite, $rang_min, $tri=null) {

        $champ_tri = null;
        $type_tri = null;

        switch($tri) {

            case 'popularite':
                $champ_tri = 'p.note_moyenne';
                $type_tri = 'DESC';
                break;

            case 'croissant':
                $champ_tri = 'p.tarif';
                $type_tri = 'ASC';
                break;

            case 'decroissant':
                $champ_tri = 'p.tarif';
                $type_tri = 'DESC';
                break;

            default:
                $champ_tri = 'p.date_creation';
                $type_tri = 'DESC';
                break;
        }

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->where('c.nom = :categorie')
            ->setParameter('categorie', $categorie)
            ->setFirstResult($rang_min)
            ->setMaxResults($quantite)
            ->addOrderBy($champ_tri, $type_tri)
            ->getQuery()
            ->getResult();
    }

    public function getBySousCategorie($categorie, $sous_categorie, $quantite, $rang_min, $tri=null) {

        $champ_tri = null;
        $type_tri = null;

        switch($tri) {

            case 'popularite':
                $champ_tri = 'p.note_moyenne';
                $type_tri = 'DESC';
                break;

            case 'croissant':
                $champ_tri = 'p.tarif';
                $type_tri = 'ASC';
                break;

            case 'decroissant':
                $champ_tri = 'p.tarif';
                $type_tri = 'DESC';
                break;

            default:
                $champ_tri = 'p.date_creation';
                $type_tri = 'DESC';
                break;
        }

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->where('c.nom = :categorie')
            ->setParameter('categorie', $categorie)
            ->leftJoin('p.categories', 'sc')
            ->andWhere('sc.nom = :sous_categorie')
            ->setParameter('sous_categorie', $sous_categorie)
            ->setFirstResult($rang_min)
            ->setMaxResults($quantite)
            ->addOrderBy($champ_tri, $type_tri)
            ->getQuery()
            ->getResult();
    }

    public function getOneByName($categorie, $sous_categorie, $produit) {

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->where('c.nom = :categorie')
            ->setParameter('categorie', $categorie)
            ->leftJoin('c.category', 'sc')
            ->andWhere('sc.nom = :sous_categorie')
            ->setParameter('sous_categorie', $sous_categorie)
            ->andWhere('p.nom = :produit')
            ->setParameter('produit', $produit)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getOneByCategorieAndName($categorie, $produit) {

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->where('c.nom = :categorie')
            ->setParameter('categorie', $categorie)
            ->andWhere('p.nom = :produit')
            ->setParameter('produit', $produit)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQuantity($categorie) {

        return $this->createQueryBuilder('p')
            ->where('p.est_publie = 1')
            ->andWhere('p.visibilite_catalogue = 1')
            ->andWhere('p.quantite_stock >= 1 OR p.commande_sans_stock = 1')
            ->leftJoin('p.categories', 'c')
            ->where('c.nom = :categorie')
            ->setParameter('categorie', $categorie)
            ->select("COUNT(p.id) as quantite")
            ->getQuery()
            ->getResult();
    }
}