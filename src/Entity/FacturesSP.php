<?php

namespace App\Entity;

use App\Repository\FacturesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturesRepository::class)
 */
class FacturesSP implements JsonSerializable
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
     * @ORM\Column(type="integer")
     */
    private $montant_total;

    /**
     * @ORM\ManyToOne(targetEntity=UsersSP::class, inversedBy="facturesSP")
     */
    private $users_id;

    /**
     * @ORM\ManyToOne(targetEntity=CodesPromoSP::class, inversedBy="facturesSP")
     */
    private $codes_promo_id;

    /**
     * @ORM\OneToMany(targetEntity=FacturesProduits::class, mappedBy="factures_id")
     */
    private $facturesProduits;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant_ht;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant_ttc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=AdressesLivraison::class, inversedBy="facture")
     */
    private $id_adresse_livraison;

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

    public function getMontantHt(): ?int
    {
        return $this->montant_ht;
    }

    public function setMontantHt(int $montant_ht): self
    {
        $this->montant_ht = $montant_ht;

        return $this;
    }

    public function getMontantTtc(): ?int
    {
        return $this->montant_ttc;
    }

    public function setMontantTtc(int $montant_ttc): self
    {
        $this->montant_ttc = $montant_ttc;

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

    public function getIdAdresseLivraison(): ?AdressesLivraison
    {
        return $this->id_adresse_livraison;
    }

    public function setIdAdresseLivraison(?AdressesLivraison $id_adresse_livraison): self
    {
        $this->id_adresse_livraison = $id_adresse_livraison;

        return $this;
    }

    public function getAdresseUser() {

        $user = $this->getUsersId();
        $adresse = [

            "prenom" => $user->getPrenom(),
            "nom" => $user->getNom(),
            "adresse" => $user->getAdresse(),
            "code_postal" => $user->getCodePostal(),
            "ville" => $user->getVille(),
            "pays" => $user->getPays()
        ];
        return $adresse;
    }

    public function getAdresseLivraison() {

        $adresse = null;
        $adresse_livraison = $this->getIdAdresseLivraison();

        if($adresse_livraison != null) {

            $adresse = [

                "prenom" => $adresse_livraison->getPrenom(),
                "nom" => $adresse_livraison->getNom(),
                "adresse" => $adresse_livraison->getAdresse(),
                "code_postal" => $adresse_livraison->getCodePostal(),
                "ville" => $adresse_livraison->getVille(),
                "pays" => $adresse_livraison->getPays()
            ];
        }
        
        return $adresse;
    }

    public function getInfosCodePromo() {

        $infos = null;
        $promo = $this->getCodesPromoId();

        if($promo != null) {

            $infos = [

                "code" => $promo->getCode(),
                "description" => $promo->getDescription(),
                "type_promo" => $promo->getTypePromo(),
                "valeur" => $promo->getValeur()
            ];
        }

        return $infos;
    }

    public function getProduitsLies() {

        $produits = [];
        $produits_lies = $this->getFacturesProduits();

        foreach($produits_lies as $produit_lie) {

            $infos = [
                'produit' => $produit_lie->getProduitsId(),
                'quantite' => $produit_lie->getQuantite()
            ];

            array_push($produits, $infos);
        }

        return $produits;
    }

    public function jsonSerialize() {

        return [
            'id' => $this->id,
            'adresse_user' => $this->getAdresseUser(),
            'adresse_livraison' => $this->getAdresseLivraison(),
            'code_promo' => $this->getInfosCodePromo(),
            'date_creation' => $this->date_creation,
            'montant_total' => $this->montant_total,
            'montant_ht' => $this->montant_ht,
            'montant_ttc' => $this->montant_ttc,
            'message' => $this->message,
            'produits' => $this->getProduitsLies()
        ];
    }
}
