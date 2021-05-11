<?php

namespace App\Entity;

use App\Repository\CodesPromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodesPromoRepository::class)
 */
class CodesPromoSP
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
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_promo; //valeurs attendues: "forfaitaire", "proportionnelle"

    /**
     * @ORM\Column(type="integer")
     */
    private $valeur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_debut_validite;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin_validite;

    /**
     * @ORM\OneToMany(targetEntity=FacturesSP::class, mappedBy="codes_promo_id")
     */
    private $factures;

    /**
     * @ORM\OneToMany(targetEntity=Paniers::class, mappedBy="codes_promo_id")
     * @ORM\JoinColumn(nullable=true)
     */
    private $paniers;

    /**
     * @ORM\OneToMany(targetEntity=CodePromoUsers::class, mappedBy="codes_promo_id")
     */
    private $codePromoUsers;

    /**
     * @ORM\Column(type="integer")
     */
    private $minimum_achat;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->codePromoUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getTypePromo(): ?string
    {
        return $this->type_promo;
    }

    public function setTypePromo(string $type_promo): self
    {
        $this->type_promo = $type_promo;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(float $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDateDebutValidite(): ?\DateTimeInterface
    {
        return $this->date_debut_validite;
    }

    public function setDateDebutValidite(\DateTimeInterface $date_debut_validite): self
    {
        $this->date_debut_validite = $date_debut_validite;

        return $this;
    }

    public function getDateFinValidite(): ?\DateTimeInterface
    {
        return $this->date_fin_validite;
    }

    public function setDateFinValidite(\DateTimeInterface $date_fin_validite): self
    {
        $this->date_fin_validite = $date_fin_validite;

        return $this;
    }

    /**
     * @return Collection|FacturesSP[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(FacturesSP $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setCodesPromoId($this);
        }

        return $this;
    }

    public function removeFacture(FacturesSP $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getCodesPromoId() === $this) {
                $facture->setCodesPromoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Paniers[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Paniers $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setCodesPromoId($this);
        }

        return $this;
    }

    public function removePanier(Paniers $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getCodesPromoId() === $this) {
                $panier->setCodesPromoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CodePromoUsers[]
     */
    public function getCodePromoUsers(): Collection
    {
        return $this->codePromoUsers;
    }

    public function addCodePromoUser(CodePromoUsers $codePromoUser): self
    {
        if (!$this->codePromoUsers->contains($codePromoUser)) {
            $this->codePromoUsers[] = $codePromoUser;
            $codePromoUser->setCodesPromoId($this);
        }

        return $this;
    }

    public function removeCodePromoUser(CodePromoUsers $codePromoUser): self
    {
        if ($this->codePromoUsers->removeElement($codePromoUser)) {
            // set the owning side to null (unless already changed)
            if ($codePromoUser->getCodesPromoId() === $this) {
                $codePromoUser->setCodesPromoId(null);
            }
        }

        return $this;
    }

    public function getMinimumAchat(): ?float
    {
        return $this->minimum_achat;
    }

    public function setMinimumAchat(float $minimum_achat): self
    {
        $this->minimum_achat = $minimum_achat;

        return $this;
    }
}
