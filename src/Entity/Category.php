<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups( 'category:read', 'instrument:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups( 'category:read', 'instrument:read')]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups( 'category:read', 'instrument:read')]
    private ?int $parent_category_id = null;

    #[ORM\Column(length: 255)]
    #[Groups( 'category:read', 'instrument:read')]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getParentCategoryId(): ?int
    {
        return $this->parent_category_id;
    }

    public function setParentCategoryId(?int $parent_category_id): static
    {
        $this->parent_category_id = $parent_category_id;

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
}
