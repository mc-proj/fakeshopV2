<?php

namespace App\Entity;

use App\Repository\CodePromoUsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodePromoUsersRepository::class)
 */
class CodePromoUsers
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
    private $date_utilisation;

    /**
     * @ORM\ManyToOne(targetEntity=CodesPromoSP::class, inversedBy="codePromoUsers")
     */
    private $codes_promo_id;

    /**
     * @ORM\ManyToOne(targetEntity=UsersSP::class, inversedBy="codePromoUsers")
     */
    private $users_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateUtilisation(): ?\DateTimeInterface
    {
        return $this->date_utilisation;
    }

    public function setDateUtilisation(\DateTimeInterface $date_utilisation): self
    {
        $this->date_utilisation = $date_utilisation;

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

    public function getUsersId(): ?usersSP
    {
        return $this->users_id;
    }

    public function setUsersId(?usersSP $users_id): self
    {
        $this->users_id = $users_id;

        return $this;
    }
}
