<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=Produits::class, inversedBy="categories_id")
     */
    private $produits;

    /**
     * @ORM\OneToMany(targetEntity=SousCategories::class, mappedBy="categories_id")
     */
    private $sous_categories_id;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->sous_categories_id = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Produits[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduits(Produits $produits): self
    {
        if (!$this->produits->contains($produits)) {
            $this->produits[] = $produits;
        }

        return $this;
    }

    public function removeProduits(Produits $produits): self
    {
        $this->produits->removeElement($produits);

        return $this;
    }

    /**
     * @return Collection|SousCategories[]
     */
    public function getSousCategoriesId(): Collection
    {
        return $this->sous_categories_id;
    }

    public function addSousCategoriesId(SousCategories $sousCategoriesId): self
    {
        if (!$this->sous_categories_id->contains($sousCategoriesId)) {
            $this->sous_categories_id[] = $sousCategoriesId;
            $sousCategoriesId->setCategoriesId($this);
        }

        return $this;
    }

    public function removeSousCategoriesId(SousCategories $sousCategoriesId): self
    {
        if ($this->sous_categories_id->removeElement($sousCategoriesId)) {
            // set the owning side to null (unless already changed)
            if ($sousCategoriesId->getCategoriesId() === $this) {
                $sousCategoriesId->setCategoriesId(null);
            }
        }

        return $this;
    }
}
