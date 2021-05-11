<?php

namespace App\Entity;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class UsersSP implements UserInterface
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
     * @ORM\Column(type="string", length=45)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_modification;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="users_id")
     */
    private $avis;

    /**
     * @ORM\OneToMany(targetEntity=FacturesSP::class, mappedBy="users_id")
     */
    private $factures;

    /**
     * @ORM\OneToMany(targetEntity=CodePromoUsers::class, mappedBy="users_id")
     */
    private $codePromoUsers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $id_stripe;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->codePromoUsers = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

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
            $avi->setUsersId($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUsersId() === $this) {
                $avi->setUsersId(null);
            }
        }

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
            $facture->setUsersId($this);
        }

        return $this;
    }

    public function removeFacture(FacturesSP $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getUsersId() === $this) {
                $facture->setUsersId(null);
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
            $codePromoUser->setUsersId($this);
        }

        return $this;
    }

    public function removeCodePromoUser(CodePromoUsers $codePromoUser): self
    {
        if ($this->codePromoUsers->removeElement($codePromoUser)) {
            // set the owning side to null (unless already changed)
            if ($codePromoUser->getUsersId() === $this) {
                $codePromoUser->setUsersId(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getIdStripe(): ?string
    {
        return $this->decode($this->id_stripe);
    }

    public function setIdStripe(?string $id_stripe): self
    {
        $this->id_stripe = $this->encode($id_stripe);
        return $this;
    }
}
