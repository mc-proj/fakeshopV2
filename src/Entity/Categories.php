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
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="category")
     */
    private $sous_categories;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, mappedBy="sous_categories")
     */
    private $category;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->sous_categories = new ArrayCollection();
        $this->category = new ArrayCollection();
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
     * @return Collection|self[]
     */
    public function getSousCategories(): Collection
    {
        return $this->sous_categories;
    }

    public function addSousCategory(self $sousCategory): self
    {
        if (!$this->sous_categories->contains($sousCategory)) {
            $this->sous_categories[] = $sousCategory;
        }

        return $this;
    }

    public function removeSousCategory(self $sousCategory): self
    {
        $this->sous_categories->removeElement($sousCategory);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(self $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
            $category->addSousCategory($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->category->removeElement($category)) {
            $category->removeSousCategory($this);
        }

        return $this;
    }
}
