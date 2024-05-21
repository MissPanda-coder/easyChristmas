<?php

namespace App\Entity;

use App\Repository\DrawRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrawRepository::class)]
class Draw
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $drawDate = null;

    #[ORM\Column]
    private ?int $drawYear = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrawDate(): ?\DateTimeInterface
    {
        return $this->drawDate;
    }

    public function setDrawDate(\DateTimeInterface $drawDate): static
    {
        $this->drawDate = $drawDate;

        return $this;
    }

    public function getDrawYear(): ?int
    {
        return $this->drawYear;
    }

    public function setDrawYear(int $drawYear): static
    {
        $this->drawYear = $drawYear;

        return $this;
    }
}
