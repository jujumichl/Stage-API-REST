<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints As Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length:8)]
    #[Assert\Regex(['pattern' => '/^[0-9]{1,8}$/', 'message' => "L'id doit comporter au minimum un et au maximum huit chiffres"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organisation $organisation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $civilite = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Regex(['pattern' => '/^[a-zA-Z]+$/', 'message' => "Le prénom doit comporter uniquement des lettres"])]
    private ?string $prenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Regex(['pattern' => '/^[a-zA-Z]+$/', 'message' => "Le nom doit comporter uniquement des lettres"])]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Regex(['pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'message' => "L'email n'est pas valide"])]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Regex(['pattern' => '/^[0-9]{10}$/', 'message' => "Le numéro de téléphone doit comporter 10 chiffres"])]
    private ?string $tel = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $fonction = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setNumeroOrganisation(?Organisation $organisation): static
    {
        $this->organisation = $organisation;

        return $this;
    }
}
