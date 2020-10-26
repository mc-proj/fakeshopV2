<?php

namespace App\Entity;

use App\Repository\TypesCaracteristiquesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypesCaracteristiquesRepository::class)
 */
class TypesCaracteristiques
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
     * @ORM\OneToMany(targetEntity=Caracteristiques::class, mappedBy="types_caracteristiques_id")
     */
    private $caracteristique_id;

    public function __construct()
    {
        $this->caracteristique_id = new ArrayCollection();
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

    /**
     * @return Collection|Caracteristiques[]
     */
    public function getCaracteristiqueId(): Collection
    {
        return $this->caracteristique_id;
    }

    public function addCaracteristiqueId(Caracteristiques $caracteristiqueId): self
    {
        if (!$this->caracteristique_id->contains($caracteristiqueId)) {
            $this->caracteristique_id[] = $caracteristiqueId;
            $caracteristiqueId->setTypesCaracteristiquesId($this);
        }

        return $this;
    }

    public function removeCaracteristiqueId(Caracteristiques $caracteristiqueId): self
    {
        if ($this->caracteristique_id->removeElement($caracteristiqueId)) {
            // set the owning side to null (unless already changed)
            if ($caracteristiqueId->getTypesCaracteristiquesId() === $this) {
                $caracteristiqueId->setTypesCaracteristiquesId(null);
            }
        }

        return $this;
    }
}
