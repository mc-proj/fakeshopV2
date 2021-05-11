<?php

namespace App\Entity;

use App\Repository\FacturesProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturesProduitsRepository::class)
 */
class FacturesProduits
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
     * @ORM\ManyToOne(targetEntity=FacturesSP::class, inversedBy="facturesProduits")
     */
    private $factures_id;

    /**
     * @ORM\ManyToOne(targetEntity=Produits::class, inversedBy="facturesProduits")
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

    public function getFacturesId(): ?facturesSP
    {
        return $this->factures_id;
    }

    public function setFacturesId(?facturesSP $factures_id): self
    {
        $this->factures_id = $factures_id;

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
