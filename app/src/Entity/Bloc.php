<?php

namespace App\Entity;

use App\Repository\BlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocRepository::class)]
class Bloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    /**
     * @var Collection<int, Stage>
     */
    #[ORM\ManyToMany(targetEntity: Stage::class, mappedBy: 'idRef')]
    private Collection $idStage;

    public function __construct()
    {
        $this->idStage = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, Stage>
     */
    public function getIdStage(): Collection
    {
        return $this->idStage;
    }

    public function addIdStage(Stage $idStage): static
    {
        if (!$this->idStage->contains($idStage)) {
            $this->idStage->add($idStage);
            $idStage->addIdRef($this);
        }

        return $this;
    }

    public function removeIdStage(Stage $idStage): static
    {
        if ($this->idStage->removeElement($idStage)) {
            $idStage->removeIdRef($this);
        }

        return $this;
    }
}
