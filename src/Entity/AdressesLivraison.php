<?php

namespace App\Entity;

use App\Repository\AdressesLivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdressesLivraisonRepository::class)
 */
class AdressesLivraison
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
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $telephone;

    /**
     * @ORM\OneToOne(targetEntity=Paniers::class, mappedBy="id_adresses_livraison", cascade={"persist", "remove"})
     */
    private $paniers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=FacturesSP::class, mappedBy="id_adresse_livraison")
     */
    private $facture;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $derniere_modification;

    public function __construct()
    {
        $this->facture = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->decode($this->nom);
    }

    public function setNom(string $nom): self
    {
        $this->nom = $this->encode($nom);
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->decode($this->prenom);
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $this->encode($prenom);
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->decode($this->adresse);
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $this->encode($adresse);
        return $this;
    }

    public function getPays(): ?string
    {
        return $this->decode($this->pays);
    }

    public function setPays(string $pays): self
    {
        $this->pays = $this->encode($pays);
        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->decode($this->code_postal);
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $this->encode($code_postal);
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->decode($this->ville);
    }

    public function setVille(string $ville): self
    {
        $this->ville = $this->encode($ville);
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->decode($this->telephone);
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $this->encode($telephone);
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->decode($this->email);
    }

    public function setEmail(string $email): self
    {
        $this->email = $this->encode($email);
        return $this;
    }

    public function getPaniers(): ?Paniers
    {
        return $this->paniers;
    }

    public function setPaniers(?Paniers $paniers): self
    {
        $this->paniers = $paniers;

        // set (or unset) the owning side of the relation if necessary
        $newId_adresses_livraison = null === $paniers ? null : $this;
        if ($paniers->getIdAdressesLivraison() !== $newId_adresses_livraison) {
            $paniers->setIdAdressesLivraison($newId_adresses_livraison);
        }

        return $this;
    }

    public function encode($donnees) {

        $donnees = serialize($donnees);
        $donnees = base64_encode($donnees);
        return $donnees;
    }

    public function decode($donnees) {

        $donnees = base64_decode($donnees);
        $donnees = unserialize($donnees);
        return $donnees;
    }

    /**
     * @return Collection|FacturesSP[]
     */
    public function getFacture(): Collection
    {
        return $this->facture;
    }

    public function addFacture(FacturesSP $facture): self
    {
        if (!$this->facture->contains($facture)) {
            $this->facture[] = $facture;
            $facture->setIdAdresseLivraison($this);
        }

        return $this;
    }

    public function removeFacture(FacturesSP $facture): self
    {
        if ($this->facture->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getIdAdresseLivraison() === $this) {
                $facture->setIdAdresseLivraison(null);
            }
        }

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDerniereModification(): ?\DateTimeInterface
    {
        return $this->derniere_modification;
    }

    public function setDerniereModification(\DateTimeInterface $derniere_modification): self
    {
        $this->derniere_modification = $derniere_modification;

        return $this;
    }
}
