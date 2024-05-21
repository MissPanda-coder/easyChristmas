<?php

namespace App\Entity;

use App\Repository\WishesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WishesRepository::class)]
class Wishes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $wishesName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $wishesYear = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWishesName(): ?string
    {
        return $this->wishesName;
    }

    public function setWishesName(string $wishesName): static
    {
        $this->wishesName = $wishesName;

        return $this;
    }

    public function getWishesYear(): ?\DateTimeInterface
    {
        return $this->wishesYear;
    }

    public function setWishesYear(\DateTimeInterface $wishesYear): static
    {
        $this->wishesYear = $wishesYear;

        return $this;
    }
}
