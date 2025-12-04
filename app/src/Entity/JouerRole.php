<?php

namespace App\Entity;

use App\Repository\JouerRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JouerRoleRepository::class)]
class JouerRole
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stage $idStage = null;

    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $idRole = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $idContact = null;


    public function getIdStage(): ?Stage
    {
        return $this->idStage;
    }

    public function setIdStage(?Stage $idStage): static
    {
        $this->idStage = $idStage;

        return $this;
    }

    public function getIdRole(): ?Role
    {
        return $this->idRole;
    }

    public function setIdRole(?Role $idRole): static
    {
        $this->idRole = $idRole;

        return $this;
    }

    public function getIdContact(): ?Contact
    {
        return $this->idContact;
    }

    public function setIdContact(?Contact $idContact): static
    {
        $this->idContact = $idContact;

        return $this;
    }
}
