<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $firstName = null;

    #[ORM\Column(length: 255)]
    protected ?string $lastName = null;

    #[ORM\Column(length: 255)]
    protected ?string $email = null;

    #[ORM\Column(length: 255)]
    protected ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    protected ?string $lastInteractions = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLastInteractions(): ?string
    {
        return $this->lastInteractions;
    }

    public function setLastInteractions(string $lastInteractions): static
    {
        $this->lastInteractions = $lastInteractions;

        return $this;
    }
}
