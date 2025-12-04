<?php

namespace App\Entity;

use App\Repository\PeriodestageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodestageRepository::class)]
class Periodestage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length:4)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateDeb = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateFin = null;

    #[ORM\Column(nullable: true, length:4)]
    private ?int $numAnneeForm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeb(): ?\DateTime
    {
        return $this->dateDeb;
    }

    public function setDateDeb(?\DateTime $dateDeb): static
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getNumAnneeForm(): ?int
    {
        return $this->numAnneeForm;
    }

    public function setNumAnneeForm(?int $numAnneeForm): static
    {
        $this->numAnneeForm = $numAnneeForm;

        return $this;
    }
}
