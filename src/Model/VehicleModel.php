<?php

namespace App\Model;

use App\Entity\Rpm;
use App\Entity\Speed;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;

class VehicleModel
{
    private object $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFreeVehicles(): array
    {
        $vehicles = $this->em->getRepository(Vehicle::class)->findBy(['device' => null]);

        $data = [];
        foreach ($vehicles as $vehicle) {
            $choiceOption = $vehicle->getMake() . ' ' . $vehicle->getModel() . ' ' . $vehicle->getYear() . "(Id: " . $vehicle->getId() .')';
            $data['data'][$choiceOption] = $vehicle->getId();
        }

        return $vehicles;
    }
}