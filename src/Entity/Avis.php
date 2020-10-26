<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvisRepository::class)
 */
class Avis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity=Produits::class, inversedBy="avis")
     */
    private $produits_id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="avis")
     */
    private $users_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getProduitsId(): ?produits
    {
        return $this->produits_id;
    }

    public function setProduitsId(?produits $produits_id): self
    {
        $this->produits_id = $produits_id;

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
}
