<?php

namespace App\Model;

use App\Entity\Device;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeviceModel
{

    private object $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function processNewDevice(array $data): bool
    {
        $device = new Device();

        $vehicle = $this->em->getRepository(Vehicle::class)->findOneBy(['id' => (int) $data['vehicle']]);

        $device
            ->setName($data['name'])
            ->setAuthenticationToken($data['authentication_token'])
            ->setVehicle($vehicle);

        try {
            $this->em->persist($device);
            $this->em->flush();
        } catch (\Exception $exception) {
            return false;
        }
        return true;
    }

    public function getVehicleChoices()
    {
        $vehicles = $this->em->getRepository(Vehicle::class)->findBy(['device' => null]);
        $vehicleChoices = [];
            foreach ($vehicles as $vehicle) {
                if ($vehicle->getDevice() == null) {
                    $vehicleChoices[$vehicle->getId()] = $vehicle->getMake() . ', ' . $vehicle->getModel() . ', ' . $vehicle->getYear();
                }

        }

        return array_flip($vehicleChoices);
    }
}