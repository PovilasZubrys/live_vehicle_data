<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $AuthenticationToken = null;

    #[ORM\OneToOne(mappedBy: 'device', cascade: ['persist', 'remove'])]
    private ?Vehicle $vehicle = null;

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

    public function getAuthenticationToken(): ?string
    {
        return $this->AuthenticationToken;
    }

    public function setAuthenticationToken(string $AuthenticationToken): static
    {
        $this->AuthenticationToken = $AuthenticationToken;

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        // unset the owning side of the relation if necessary
        if ($vehicle === null && $this->vehicle !== null) {
            $this->vehicle->setDevice(null);
        }

        // set the owning side of the relation if necessary
        if ($vehicle !== null && $vehicle->getDevice() !== $this) {
            $vehicle->setDevice($this);
        }

        $this->vehicle = $vehicle;

        return $this;
    }
}
