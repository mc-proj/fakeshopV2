<?php

namespace App\Entity;

use App\Repository\PaniersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaniersRepository::class)
 */
class Paniers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $commande_terminee;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\OneToOne(targetEntity=Users::class, cascade={"persist", "remove"})
     */
    private $users_id;

    /**
     * @ORM\ManyToOne(targetEntity=CodesPromo::class, inversedBy="paniers")
     */
    private $codes_promo_id;

    /**
     * @ORM\OneToMany(targetEntity=PaniersProduits::class, mappedBy="paniers_id")
     */
    private $paniersProduits;

    public function __construct()
    {
        $this->paniersProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommandeTerminee(): ?bool
    {
        return $this->commande_terminee;
    }

    public function setCommandeTerminee(bool $commande_terminee): self
    {
        $this->commande_terminee = $commande_terminee;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getUsersId(): ?users
    {
        return $this->users_id;
    }

    public function setUsersId(?users $users_id): self
    {
        $this->users_id = $users_id;

        return $this;
    }

    public function getCodesPromoId(): ?codesPromo
    {
        return $this->codes_promo_id;
    }

    public function setCodesPromoId(?codesPromo $codes_promo_id): self
    {
        $this->codes_promo_id = $codes_promo_id;

        return $this;
    }

    /**
     * @return Collection|PaniersProduits[]
     */
    public function getPaniersProduits(): Collection
    {
        return $this->paniersProduits;
    }

    public function addPaniersProduit(PaniersProduits $paniersProduit): self
    {
        if (!$this->paniersProduits->contains($paniersProduit)) {
            $this->paniersProduits[] = $paniersProduit;
            $paniersProduit->setPaniersId($this);
        }

        return $this;
    }

    public function removePaniersProduit(PaniersProduits $paniersProduit): self
    {
        if ($this->paniersProduits->removeElement($paniersProduit)) {
            // set the owning side to null (unless already changed)
            if ($paniersProduit->getPaniersId() === $this) {
                $paniersProduit->setPaniersId(null);
            }
        }

        return $this;
    }
}
