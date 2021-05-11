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
     * @ORM\Column(type="integer")
     */
    private $montant_ht;

    /**
     * @ORM\OneToOne(targetEntity=UsersSP::class)
     */
    private $users_id;

    /**
     * @ORM\ManyToOne(targetEntity=CodesPromoSP::class, inversedBy="paniers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $codes_promo_id;

    /**
     * @ORM\OneToMany(targetEntity=PaniersProduits::class, mappedBy="paniers_id")
     */
    private $paniersProduits;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant_ttc;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_modification;

    /**
     * @ORM\OneToOne(targetEntity=AdressesLivraison::class, inversedBy="paniers", cascade={"persist", "remove"})
     */
    private $id_adresses_livraison;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

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

    public function getMontantHt(): ?float
    {
        return $this->montant_ht;
    }

    public function setMontantHt(float $montant_ht): self
    {
        $this->montant_ht = $montant_ht;

        return $this;
    }

    public function getUsersId(): ?usersSP
    {
        return $this->users_id;
    }

    public function setUsersId(?usersSP $users_id): self
    {
        $this->users_id = $users_id;

        return $this;
    }

    public function getCodesPromoId(): ?codesPromoSP
    {
        return $this->codes_promo_id;
    }

    public function setCodesPromoId(?codesPromoSP $codes_promo_id): self
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

    public function getMontantTtc(): ?float
    {
        return $this->montant_ttc;
    }

    public function setMontantTtc(float $montant_ttc): self
    {
        $this->montant_ttc = $montant_ttc;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

        return $this;
    }

    public function getIdAdressesLivraison(): ?AdressesLivraison
    {
        return $this->id_adresses_livraison;
    }

    public function setIdAdressesLivraison(?AdressesLivraison $id_adresses_livraison): self
    {
        $this->id_adresses_livraison = $id_adresses_livraison;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
