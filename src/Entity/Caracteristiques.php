<?php

namespace App\Entity;

use App\Repository\CaracteristiquesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CaracteristiquesRepository::class)
 */
class Caracteristiques
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity=Produits::class, inversedBy="types_caracteristiques_id")
     */
    private $produits_id;

    /**
     * @ORM\ManyToOne(targetEntity=TypesCaracteristiques::class, inversedBy="caracteristique_id")
     */
    private $types_caracteristiques_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getProduitsId(): ?Produits
    {
        return $this->produits_id;
    }

    public function setProduitsId(?Produits $produits_id): self
    {
        $this->produits_id = $produits_id;

        return $this;
    }

    public function getTypesCaracteristiquesId(): ?typesCaracteristiques
    {
        return $this->types_caracteristiques_id;
    }

    public function setTypesCaracteristiquesId(?typesCaracteristiques $types_caracteristiques_id): self
    {
        $this->types_caracteristiques_id = $types_caracteristiques_id;

        return $this;
    }
}
