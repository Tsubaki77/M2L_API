<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: 'admin')]
#[ORM\HasLifecycleCallbacks]
class Admin implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, unique: true)] // Unique pour éviter les doublons
    private ?string $identifiant = null;

   
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;


    public function getUserIdentifier(): string
    {
        return (string) $this->identifiant;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // On garantit que chaque admin a au moins le rôle ROLE_ADMIN
        $roles[] = 'ROLE_ADMIN';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si tu stockes des données sensibles temporairement, efface-les ici
    }

    // --- LES AUTRES GETTERS / SETTERS ---

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getIdentifiant(): ?string { return $this->identifiant; }
    public function setIdentifiant(string $identifiant): static { $this->identifiant = $identifiant; return $this; }

    #[ORM\PrePersist]
    public function onPrePersist(): void { $this->createdAt = new \DateTime(); }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void { $this->updatedAt = new \DateTime(); }
}