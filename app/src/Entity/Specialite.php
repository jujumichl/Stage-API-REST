<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:'NONE')]
    #[ORM\Column(length:1)]
    private ?string $ref = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $sigle = null;

    #[ORM\Column(length: 60)]
    private ?string $intitule = null;

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(?string $sigle): static
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }
}
