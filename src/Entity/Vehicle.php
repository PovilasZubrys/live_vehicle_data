<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $make = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $year = null;

    #[ORM\OneToMany(targetEntity: Speed::class, mappedBy: 'vehicle', orphanRemoval: true)]
    private Collection $speeds;

    #[ORM\OneToMany(targetEntity: Rpm::class, mappedBy: 'vehicle')]
    private Collection $rpms;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Device $device = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->speeds = new ArrayCollection();
        $this->rpms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): static
    {
        $this->make = $make;

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

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, Speed>
     */
    public function getSpeeds(): Collection
    {
        return $this->speeds;
    }

    public function addSpeed(Speed $speed): static
    {
        if (!$this->speeds->contains($speed)) {
            $this->speeds->add($speed);
            $speed->setVehicle($this);
        }

        return $this;
    }

    public function removeSpeed(Speed $speed): static
    {
        if ($this->speeds->removeElement($speed)) {
            // set the owning side to null (unless already changed)
            if ($speed->getVehicle() === $this) {
                $speed->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rpm>
     */
    public function getRpms(): Collection
    {
        return $this->rpms;
    }

    public function addRpm(Rpm $rpm): static
    {
        if (!$this->rpms->contains($rpm)) {
            $this->rpms->add($rpm);
            $rpm->setVehicle($this);
        }

        return $this;
    }

    public function removeRpm(Rpm $rpm): static
    {
        if ($this->rpms->removeElement($rpm)) {
            // set the owning side to null (unless already changed)
            if ($rpm->getVehicle() === $this) {
                $rpm->setVehicle(null);
            }
        }

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): static
    {
        $this->device = $device;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
