<?php

namespace App\Entity;

use App\Repository\FacturesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturesRepository::class)
 */
class Factures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="float")
     */
    private $montant_total;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="factures")
     */
    private $users_id;

    /**
     * @ORM\ManyToOne(targetEntity=CodesPromo::class, inversedBy="factures")
     */
    private $codes_promo_id;

    /**
     * @ORM\OneToMany(targetEntity=FacturesProduits::class, mappedBy="factures_id")
     */
    private $facturesProduits;

    public function __construct()
    {
        $this->facturesProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontantTotal(): ?float
    {
        return $this->montant_total;
    }

    public function setMontantTotal(float $montant_total): self
    {
        $this->montant_total = $montant_total;

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
     * @return Collection|FacturesProduits[]
     */
    public function getFacturesProduits(): Collection
    {
        return $this->facturesProduits;
    }

    public function addFacturesProduit(FacturesProduits $facturesProduit): self
    {
        if (!$this->facturesProduits->contains($facturesProduit)) {
            $this->facturesProduits[] = $facturesProduit;
            $facturesProduit->setFacturesId($this);
        }

        return $this;
    }

    public function removeFacturesProduit(FacturesProduits $facturesProduit): self
    {
        if ($this->facturesProduits->removeElement($facturesProduit)) {
            // set the owning side to null (unless already changed)
            if ($facturesProduit->getFacturesId() === $this) {
                $facturesProduit->setFacturesId(null);
            }
        }

        return $this;
    }
}
