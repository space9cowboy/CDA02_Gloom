<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(groups: ['user:read','instrument:read', 'review:read'])]

    private ?int $id = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: "json")]

    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read'])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups( 'user:read', 'instrument:read', 'review:read')]
    private ?string $image = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // #[ORM\Column]
    // private ?\DateTimeImmutable $updatedAt = null; // Corrigé de updateAt à updatedAt

    #[ORM\Column(nullable: true)]
    #[Groups(groups: ['user:read','instrument:read', 'review:read'])]
    private ?float $rating = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class, cascade: ['persist', 'remove'])]
    #[Groups(groups: ['user:read', 'instrument:read', 'review:read'])]
    #[MaxDepth(1)]
    private Collection $reviewsSend; // Avis donnés par l'utilisateur

    #[ORM\OneToMany(mappedBy: 'userNoted', targetEntity: Review::class, cascade: ['persist', 'remove'])]
    #[Groups(groups: ['user:read', 'instrument:read', 'review:read'])]
    #[MaxDepth(1)]
    private Collection $reviewsReceive; // Avis reçus par l'utilisateur

    #[ORM\ManyToMany(targetEntity: Instrument::class)]
    #[ORM\JoinTable(name: "user_favorites")] // Table pivot pour gérer les favoris
    #[Groups(['user:read',  'instrument:read'])]
    #[MaxDepth(1)]
    private Collection $favoris;


    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->reviewsSend = new ArrayCollection();
        $this->reviewsReceive = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username; // En général, l'email est l'identifiant de l'utilisateur
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si des données sensibles doivent être effacées après authentification
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // public function getUpdatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->updatedAt;
    // }

    // public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    // {
    //     $this->updatedAt = $updatedAt;

    //     return $this;
    // }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviewsSend(): Collection
    {
        return $this->reviewsSend;
    }

    public function addReviewSend(Review $review): static
    {
        $this->reviewsSend[] = $review;
        $review->setUser($this);
        return $this;
    }

    public function getReviewsReceive(): Collection
    {
        return $this->reviewsReceive;
    }

    public function addReviewReceive(Review $review): static
    {
        $this->reviewsReceive[] = $review;
        $review->setUserNoted($this);
        return $this;
    }

    /**
     * @return Collection<int, Instrument>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Instrument $instrument): static
    {
        if (!$this->favoris->contains($instrument)) {
            $this->favoris->add($instrument);
        }

        return $this;
    }

    public function removeFavori(Instrument $instrument): static
    {
        $this->favoris->removeElement($instrument);

        return $this;
    }
}
