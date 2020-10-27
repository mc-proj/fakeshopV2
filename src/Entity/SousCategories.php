<?php

namespace App\Entity;

use App\Repository\SousCategoriesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SousCategoriesRepository::class)
 */
class SousCategories
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
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="sous_categories_id")
     */
    private $categories_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCategoriesId(): ?Categories
    {
        return $this->categories_id;
    }

    public function setCategoriesId(?Categories $categories_id): self
    {
        $this->categories_id = $categories_id;

        return $this;
    }
}
