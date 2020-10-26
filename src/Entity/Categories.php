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
     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="categories")
     */
    private $produits_id;

    public function __construct()
    {
        $this->produits_id = new ArrayCollection();
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
     * @return Collection|produits[]
     */
    public function getProduitsId(): Collection
    {
        return $this->produits_id;
    }

    public function addProduitsId(produits $produitsId): self
    {
        if (!$this->produits_id->contains($produitsId)) {
            $this->produits_id[] = $produitsId;
            $produitsId->setCategories($this);
        }

        return $this;
    }

    public function removeProduitsId(produits $produitsId): self
    {
        if ($this->produits_id->removeElement($produitsId)) {
            // set the owning side to null (unless already changed)
            if ($produitsId->getCategories() === $this) {
                $produitsId->setCategories(null);
            }
        }

        return $this;
    }
}
