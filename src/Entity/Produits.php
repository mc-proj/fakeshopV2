<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produits
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
    private $UGS;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="boolean")
     */
    private $est_publie;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mis_en_avant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibilite_catalogue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description_courte;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_debut_promo;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_fin_promo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat_tva;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite_stock;

    /**
     * @ORM\Column(type="integer")
     */
    private $limite_basse_stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $commande_sans_stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $vente_individuelle;

    /**
     * @ORM\Column(type="boolean")
     */
    private $est_evaluable;

    /**
     * @ORM\Column(type="float")
     */
    private $tarif;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tarif_promo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $delai_telechargement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombre_telechargements;

    /**
     * @ORM\OneToMany(targetEntity=Caracteristiques::class, mappedBy="produits_id")
     */
    private $types_caracteristiques_id;

    /**
     * @ORM\ManyToOne(targetEntity=TauxTva::class, inversedBy="produits")
     */
    private $taux_tva_id;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="produits_id")
     */
    private $avis;

    /**
     * @ORM\OneToMany(targetEntity=PaniersProduits::class, mappedBy="produits_id")
     */
    private $paniersProduits;

    /**
     * @ORM\OneToMany(targetEntity=FacturesProduits::class, mappedBy="produits_id")
     */
    private $facturesProduits;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, mappedBy="produits_id")
     */
    private $categories;

    public function __construct()
    {
        $this->types_caracteristiques_id = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->paniersProduits = new ArrayCollection();
        $this->facturesProduits = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUGS(): ?string
    {
        return $this->UGS;
    }

    public function setUGS(string $UGS): self
    {
        $this->UGS = $UGS;

        return $this;
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

    public function getEstPublie(): ?bool
    {
        return $this->est_publie;
    }

    public function setEstPublie(bool $est_publie): self
    {
        $this->est_publie = $est_publie;

        return $this;
    }

    public function getMisEnAvant(): ?bool
    {
        return $this->mis_en_avant;
    }

    public function setMisEnAvant(bool $mis_en_avant): self
    {
        $this->mis_en_avant = $mis_en_avant;

        return $this;
    }

    public function getVisibiliteCatalogue(): ?bool
    {
        return $this->visibilite_catalogue;
    }

    public function setVisibiliteCatalogue(bool $visibilite_catalogue): self
    {
        $this->visibilite_catalogue = $visibilite_catalogue;

        return $this;
    }

    public function getDescriptionCourte(): ?string
    {
        return $this->description_courte;
    }

    public function setDescriptionCourte(string $description_courte): self
    {
        $this->description_courte = $description_courte;

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

    public function getDateDebutPromo(): ?\DateTimeInterface
    {
        return $this->date_debut_promo;
    }

    public function setDateDebutPromo(?\DateTimeInterface $date_debut_promo): self
    {
        $this->date_debut_promo = $date_debut_promo;

        return $this;
    }

    public function getDateFinPromo(): ?\DateTimeInterface
    {
        return $this->date_fin_promo;
    }

    public function setDateFinPromo(?\DateTimeInterface $date_fin_promo): self
    {
        $this->date_fin_promo = $date_fin_promo;

        return $this;
    }

    public function getEtatTva(): ?bool
    {
        return $this->etat_tva;
    }

    public function setEtatTva(bool $etat_tva): self
    {
        $this->etat_tva = $etat_tva;

        return $this;
    }

    public function getQuantiteStock(): ?int
    {
        return $this->quantite_stock;
    }

    public function setQuantiteStock(int $quantite_stock): self
    {
        $this->quantite_stock = $quantite_stock;

        return $this;
    }

    public function getLimiteBasseStock(): ?int
    {
        return $this->limite_basse_stock;
    }

    public function setLimiteBasseStock(int $limite_basse_stock): self
    {
        $this->limite_basse_stock = $limite_basse_stock;

        return $this;
    }

    public function getCommandeSansStock(): ?bool
    {
        return $this->commande_sans_stock;
    }

    public function setCommandeSansStock(bool $commande_sans_stock): self
    {
        $this->commande_sans_stock = $commande_sans_stock;

        return $this;
    }

    public function getVenteIndividuelle(): ?bool
    {
        return $this->vente_individuelle;
    }

    public function setVenteIndividuelle(bool $vente_individuelle): self
    {
        $this->vente_individuelle = $vente_individuelle;

        return $this;
    }

    public function getEstEvaluable(): ?bool
    {
        return $this->est_evaluable;
    }

    public function setEstEvaluable(bool $est_evaluable): self
    {
        $this->est_evaluable = $est_evaluable;

        return $this;
    }

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getTarifPromo(): ?float
    {
        return $this->tarif_promo;
    }

    public function setTarifPromo(?float $tarif_promo): self
    {
        $this->tarif_promo = $tarif_promo;

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

    public function getDelaiTelechargement(): ?int
    {
        return $this->delai_telechargement;
    }

    public function setDelaiTelechargement(?int $delai_telechargement): self
    {
        $this->delai_telechargement = $delai_telechargement;

        return $this;
    }

    public function getNombreTelechargements(): ?int
    {
        return $this->nombre_telechargements;
    }

    public function setNombreTelechargements(?int $nombre_telechargements): self
    {
        $this->nombre_telechargements = $nombre_telechargements;

        return $this;
    }

    /**
     * @return Collection|Caracteristiques[]
     */
    public function getTypesCaracteristiquesId(): Collection
    {
        return $this->types_caracteristiques_id;
    }

    public function addTypesCaracteristiquesId(Caracteristiques $typesCaracteristiquesId): self
    {
        if (!$this->types_caracteristiques_id->contains($typesCaracteristiquesId)) {
            $this->types_caracteristiques_id[] = $typesCaracteristiquesId;
            $typesCaracteristiquesId->setProduitsId($this);
        }

        return $this;
    }

    public function removeTypesCaracteristiquesId(Caracteristiques $typesCaracteristiquesId): self
    {
        if ($this->types_caracteristiques_id->removeElement($typesCaracteristiquesId)) {
            // set the owning side to null (unless already changed)
            if ($typesCaracteristiquesId->getProduitsId() === $this) {
                $typesCaracteristiquesId->setProduitsId(null);
            }
        }

        return $this;
    }

    public function getTauxTvaId(): ?tauxtva
    {
        return $this->taux_tva_id;
    }

    public function setTauxTvaId(?tauxtva $taux_tva_id): self
    {
        $this->taux_tva_id = $taux_tva_id;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setProduitsId($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduitsId() === $this) {
                $avi->setProduitsId(null);
            }
        }

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
            $paniersProduit->setProduitsId($this);
        }

        return $this;
    }

    public function removePaniersProduit(PaniersProduits $paniersProduit): self
    {
        if ($this->paniersProduits->removeElement($paniersProduit)) {
            // set the owning side to null (unless already changed)
            if ($paniersProduit->getProduitsId() === $this) {
                $paniersProduit->setProduitsId(null);
            }
        }

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
            $facturesProduit->setProduitsId($this);
        }

        return $this;
    }

    public function removeFacturesProduit(FacturesProduits $facturesProduit): self
    {
        if ($this->facturesProduits->removeElement($facturesProduit)) {
            // set the owning side to null (unless already changed)
            if ($facturesProduit->getProduitsId() === $this) {
                $facturesProduit->setProduitsId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategories(Categories $categories): self
    {
        if (!$this->categories->contains($categories)) {
            $this->categories[] = $categories;
            $categories->addProduits($this);
        }

        return $this;
    }

    public function removeCategories(Categories $categories): self
    {
        if ($this->categories_id->removeElement($categories)) {
            $categories->removeProduits($this);
        }

        return $this;
    }
}
