<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $make;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $model;

    /**
     * @ORM\Column(type="date")
     */
    private \DateTimeInterface $buildDate;

    /**
     * @ORM\ManyToOne(targetEntity=Color::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Color $color;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBuildDate(): ?\DateTimeInterface
    {
        return $this->buildDate;
    }

    public function setBuildDate(\DateTimeInterface $buildDate): self
    {
        $this->buildDate = $buildDate;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }
}
