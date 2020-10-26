<?php

namespace App\Entity;

use App\Repository\PaniersProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaniersProduitsRepository::class)
 */
class PaniersProduits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Paniers::class, inversedBy="paniersProduits")
     */
    private $paniers_id;

    /**
     * @ORM\ManyToOne(Produits::class, inversedBy="paniersProduits")
     */
    private $produits_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPaniersId(): ?paniers
    {
        return $this->paniers_id;
    }

    public function setPaniersId(?paniers $paniers_id): self
    {
        $this->paniers_id = $paniers_id;

        return $this;
    }

    public function getProduitsId(): ?produits
    {
        return $this->produits_id;
    }

    public function setProduitsId(?produits $produits_id): self
    {
        $this->produits_id = $produits_id;

        return $this;
    }
}
