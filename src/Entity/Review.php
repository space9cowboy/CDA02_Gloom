<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('user:read')]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    #[Groups('user:read')]
    private ?float $rating = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('review:read')]
    private ?User $user = null;  // Auteur de l'avis

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('review:read')]
    private ?User $userNoted = null;  // Utilisateur qui est lié

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUserNoted(): ?User
    {
        return $this->userNoted;
    }

    public function setUserNoted(User $userNoted): static
    {
        $this->userNoted = $userNoted;

        return $this;
    }
}
