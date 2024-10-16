<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Instrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $description = null;

    #[ORM\Column(type: 'float')]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $category = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?string $location = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?User $seller = null; // Relation avec l'entité User

    #[ORM\Column]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(groups: ['user:read','instrument:read', 'review:read', 'transaction:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?bool $isSold = null;

    // #[ORM\Column(nullable: true)]
    // #[Groups('instrument:read')]
    // private ?float $rating = null;

    // #[ORM\OneToMany(mappedBy: 'instrument', targetEntity: Review::class, cascade: ['persist', 'remove'])]
    // #[Groups('instrument:read')]
    // private $reviews;  // Relation avec les avis

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): static
    {
        $this->seller = $seller;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

public function isSold(): ?bool
{
    return $this->isSold;
}

public function setSold(bool $isSold): static
{
    $this->isSold = $isSold;

    return $this;
}
}
